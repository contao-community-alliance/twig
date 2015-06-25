<?php

/**
 * Twig Integration for the Contao OpenSource CMS
 *
 * @package ContaoTwig
 * @link    https://github.com/bit3/contao-twig SCM
 * @link    http://de.contaowiki.org/Twig Wiki
 * @author  Tristan Lins <tristan.lins@bit3.de>
 * @author  Oliver Hoff <oliver@hofff.com>
 * @author  Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */
use Database\Result;
use Database\Statement;

/**
 * Class ContaoTwig
 *
 * Set up the twig environment and provide the template loaders.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
class ContaoTwigExtension extends Controller implements Twig_ExtensionInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Initializes the runtime environment.
     *
     * This is where you can load some file that contains filter functions for instance.
     *
     * @param Twig_Environment $environment The current Twig_Environment instance
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function initRuntime(Twig_Environment $environment)
    {
    }

    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
     */
    public function getTokenParsers()
    {
        return array();
    }

    /**
     * Returns the node visitor instances to add to the existing list.
     *
     * @return array An array of Twig_NodeVisitorInterface instances
     */
    public function getNodeVisitors()
    {
        return array();
    }

    /**
     * Returns a list of tests to add to the existing list.
     *
     * @return array An array of tests
     */
    public function getTests()
    {
        return array();
    }

    /**
     * Returns a list of operators to add to the existing list.
     *
     * @return array An array of operators
     */
    public function getOperators()
    {
        return array();
    }

    /**
     *
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function getGlobals()
    {
        $globals = array(
            'REQUEST_TOKEN' => REQUEST_TOKEN,
            '_session'      => new ContaoTwigGlobalAccessObject('_SESSION'),
            '_lang'         => new ContaoTwigGlobalAccessObject('TL_LANG'),
            '_dca'          => new ContaoTwigGlobalAccessObject('TL_DCA'),
            '_config'       => new ContaoTwigGlobalAccessObject('TL_CONFIG'),
            '_env'          => new ContaoTwigEnvironmentAccessObject(),
            '_referer'      => new ContaoTwigRefererAccessObject(),
            '_db'           => Database::getInstance(),
            '_page'         => $GLOBALS['objPage'],
            '_member'       => $this->getMember(),
            '_user'         => $this->getUser(),
            '_theme'        => $this->getThemeName()
        );

        if (version_compare(VERSION, '3.1', '>=')) {
            $globals['REFERER_ID'] = TL_REFERER_ID;
        }

        return $globals;
    }

    public function getFilters()
    {
        return array(
            'deserialize' => new Twig_Filter_Function('deserialize'),
            'standardize' => new Twig_Filter_Function('standardize'),
            'date'        => new Twig_Filter_Function(
                array(
                    $this,
                    '_dateFilter'
                ), array('needs_environment' => true)
            ),
            'dateFormat'  => new Twig_Filter_Function(
                array(
                    $this,
                    '_parseDateFilter'
                )
            ),
            'datimFormat' => new Twig_Filter_Function(
                array(
                    $this,
                    '_parseDatimFilter'
                )
            ),
            'prepare'     => new Twig_Filter_Function(
                array(
                    $this,
                    '_prepareFilter'
                )
            ),
            'set'         => new Twig_Filter_Function(
                array(
                    $this,
                    '_setFilter'
                )
            ),
            'execute'     => new Twig_Filter_Function(
                array(
                    $this,
                    '_executeFilter'
                )
            ),
            'query'       => new Twig_Filter_Function(
                array(
                    $this,
                    '_queryFilter'
                )
            ),
            'vformat'     => new Twig_Filter_Function(
                array(
                    $this,
                    '_vformat'
                )
            ),
            'url'         => new Twig_Filter_Function(
                array(
                    $this,
                    '_generateUrl'
                )
            ),
            'image'       => new Twig_Filter_Function(
                array(
                    $this,
                    '_addImage'
                )
            ),
            'stringify'   => new Twig_Filter_Function(
                array(
                    $this,
                    '_stringify'
                ), array('needs_environment' => true)
            ),
        );
    }

    public function getFunctions()
    {
        return array(
            'image'    => new Twig_Function_Function(
                array(
                    $this,
                    '_addImage'
                )
            ),
            'messages' => new Twig_Function_Function(
                array(
                    $this,
                    '_getMessages'
                )
            ),
            'addToUrl' => new Twig_Function_Function(
                array(
                    $this,
                    '_addToUrl'
                )
            ),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'contao';
    }

    /**
     *
     *
     * @param Twig_Environment $env
     * @param                  $date
     * @param null             $format
     * @param null             $timezone
     *
     * @return mixed|string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function _dateFilter(\Twig_Environment $env, $date, $format = null, $timezone = null)
    {
        $string = twig_date_format_filter($env, $date, $format, $timezone);

        $search  = array();
        $replace = array();

        if (null === $format) {
            $formats = $env
                ->getExtension('core')
                ->getDateFormat();
            $format  = $date instanceof DateInterval ? $formats[1] : $formats[0];
        }

        if (strpos($format, 'F') !== false) {
            for ($month = 1; $month <= 12; $month++) {
                $time      = mktime(0, 0, 0, $month);
                $search[]  = date('F', $time);
                $replace[] = strftime('%B', $time);
            }
        }
        if (strpos($format, 'M') !== false) {
            for ($month = 1; $month <= 12; $month++) {
                $time      = mktime(0, 0, 0, $month);
                $search[]  = date('M', $time);
                $replace[] = strftime('%h', $time);
            }
        }
        if (strpos($format, 'l') !== false) {
            $date = new DateTime();

            // go to first day of week
            $date->sub(new DateInterval('P' . ($date->format('N') - 1) . 'D'));

            for ($i = 0; $i < 7; $i++) {
                $date->add(new DateInterval('P1D'));
                $search[]  = $date->format('l');
                $replace[] = strftime('%A', $date->getTimestamp());
            }
        }
        if (strpos($format, 'D') !== false) {
            $date = new DateTime();

            // go to first day of week
            $date->sub(new DateInterval('P' . ($date->format('N') - 1) . 'D'));

            for ($i = 0; $i < 7; $i++) {
                $date->add(new DateInterval('P1D'));
                $search[]  = $date->format('D');
                $replace[] = strftime('%a', $date->getTimestamp());
            }
        }

        if (count($search)) {
            $string = str_replace(
                $search,
                $replace,
                $string
            );
        }

        return $string;
    }

    /**
     * Parse the timestamp with the default date format.
     *
     * @static
     *
     * @param int $timestamp
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function _parseDateFilter($timestamp)
    {
        if ($timestamp instanceof \DateTime) {
            $timestamp = $timestamp->getTimestamp();
        }

        return $this
            ->parseDate(
                $GLOBALS['TL_CONFIG']['dateFormat'],
                $timestamp
            );
    }

    /**
     * Parse the timestamp with the default date and time format.
     *
     * @static
     *
     * @param int $timestamp
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function _parseDatimFilter($timestamp)
    {
        if ($timestamp instanceof \DateTime) {
            $timestamp = $timestamp->getTimestamp();
        }

        return $this
            ->parseDate(
                $GLOBALS['TL_CONFIG']['datimFormat'],
                $timestamp
            );
    }

    /**
     * Prepare a database statement.
     *
     * @static
     *
     * @param string $sql
     *
     * @return Statement
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function _prepareFilter($sql)
    {
        return Database::getInstance()
            ->prepare($sql);
    }

    /**
     * Set database statement update arguments.
     *
     * @static
     *
     * @param Statement $statement
     * @param array     $arguments
     *
     * @return Statement
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function _setFilter(
        Statement $statement,
        array $arguments
    ) {
        return $statement->set($arguments);
    }

    /**
     * Execute a database statement.
     *
     * @static
     *
     * @param string|Statement $statement
     * @param array                     $arguments
     *
     * @return Result
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function _executeFilter(
        $statement,
        array $arguments = array()
    ) {
        if ($statement instanceof Statement) {
            /** @var Statement $statement */
            return $statement
                ->execute($arguments)
                ->fetchAllAssoc();
        } else {
            return Database::getInstance()
                ->execute($statement)
                ->fetchAllAssoc();
        }
    }

    /**
     * Execute a database query.
     *
     * @static
     *
     * @param string $statement
     *
     * @return Result
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function _queryFilter($statement)
    {
        return Database::getInstance()
            ->query($statement)
            ->fetchAllAssoc();
    }

    /**
     * Add an image
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function _addImage()
    {
        $arguments = func_get_args();
        if (is_array($arguments) && is_array($arguments[1])) {
            $src        = $arguments[0];
            $width      = $arguments[1]['width'];
            $height     = $arguments[1]['height'];
            $mode       = $arguments[1]['mode'];
            $alt        = $arguments[1]['alt'];
            $attributes = $arguments[1]['attributes'];
            $fallback   = $arguments[1]['fallback'];
        } else {
            if (is_array($arguments)) {
                list($src, $width, $height, $mode, $alt, $attributes) = $arguments;
                $fallback = false;
            } else {
                $src        = $arguments;
                $width      = '';
                $height     = '';
                $mode       = '';
                $alt        = '';
                $attributes = '';
                $fallback   = false;
            }
        }

        if (version_compare(VERSION, '3', '>=')) {
            $file = FilesModel::findByPk($src);

            if ($file) {
                $src = $file->path;
            }
        }

        if (!ctype_print($src) || !file_exists(TL_ROOT . '/' . $src)) {
            if ($fallback) {
                unset($arguments[1]['fallback']);

                return $this->_addImage($fallback, $arguments[1]);
            }

            return '';
        }

        $file        = new File($src);
        $intMaxWidth = (TL_MODE == 'BE') ? 320 : $GLOBALS['TL_CONFIG']['maxImageWidth'];
        if ($intMaxWidth > 0 && ($width > $intMaxWidth || $file->width > $intMaxWidth)) {
            $width  = $intMaxWidth;
            $height = null;
        }

        if ($width || $height) {
            $src = $this->getImage(
                $src,
                $width,
                $height,
                $mode
            );
        }

        return $this->generateImage(
            $src,
            $alt,
            $attributes
        );
    }

    /**
     * Get all messages as string.
     *
     * @param array $arguments
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function _getMessages($arguments = array())
    {
        return $this
            ->getMessages(
                isset($arguments[0]) ? $arguments[0] : false,
                isset($arguments[1]) ? $arguments[1] : false
            );
    }

    /**
     * @param string|array $parameters
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function _addToUrl($parameters, $addRefererId = true, $unsetParameters = array())
    {
        if (is_array($parameters)) {
            $parameters = http_build_query($parameters);
        }

        if (TL_MODE == 'BE') {
            return \Backend::addToUrl($parameters, $addRefererId, $unsetParameters);
        }

        return \Controller::addToUrl($parameters, $addRefererId, $unsetParameters);
    }

    /**
     * Format with an array
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function _vformat($format, $arguments)
    {
        return vsprintf($format, (array)$arguments);
    }

    /**
     * Generate a frontend url
     *
     * @param mixed $page
     * @param null  $params
     * @param null  $language
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function _generateUrl($page, $params = null, $language = null)
    {
        if ($page instanceof Result) {
            $page = $page->row();
        } else {
            if ($page instanceof \Model\Collection) {
                $page = $page->current()->row();
            } else {
                if ($page instanceof Model) {
                    $page = $page->row();
                } else {
                    if (is_numeric($page)) {
                        $page = Database::getInstance()
                            ->prepare('SELECT * FROM tl_page WHERE id=?')
                            ->execute($page)
                            ->fetchAssoc();
                    }
                }
            }
        }

        if (!is_array($page)) {
            return '';
        }

        return $this->generateFrontendUrl(
            $page,
            $params,
            $language
        );
    }

    /**
     * Stringify a value and make it human readable.
     *
     * @param Twig_Environment $env
     * @param mixed            $value
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function _stringify(\Twig_Environment $env, $value)
    {
        if (is_object($value)) {
            if ($value instanceof ArrayObject) {
                $value = $value->getArrayCopy();
            } else {
                if ($value instanceof \Doctrine\Common\Collections\Collection) {
                    $value = $value->toArray();
                } else {
                    if ($value instanceof DateTime) {
                        return $this->_dateFilter($env, $value);
                    } else {
                        return '{' . get_class($value) . '}';
                    }
                }
            }
        }
        if (is_array($value)) {
            $self   = $this;
            $values = array_map(
                function ($value) use ($self, $env) {
                    return $self->_stringify($env, $value);
                },
                $value
            );

            return '[' . implode(', ', $values) . ']';
        }
        if (is_null($value)) {
            return 'NULL';
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_double($value) && is_infinite($value)) {
            return '&infin;';
        }

        return $value;
    }

    /**
     *
     *
     * @return bool|User
     */
    private function getMember()
    {
        return TL_MODE == 'FE' && FE_USER_LOGGED_IN
            ? FrontendUser::getInstance()
            : false;
    }

    /**
     *
     *
     * @return bool|User
     */
    private function getUser()
    {
        return TL_MODE == 'BE' && BE_USER_LOGGED_IN
            ? BackendUser::getInstance()
            : false;
    }

    /**
     *
     *
     * @return bool|string
     */
    private function getThemeName()
    {
        return TL_MODE == 'BE'
            ? \Backend::getTheme()
            : false;
    }
}
