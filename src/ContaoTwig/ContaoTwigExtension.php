<?php

/**
 * Twig Integration for the Contao OpenSource CMS
 *
 * @package   ContaoTwig
 * @author    Tristan Lins <tristan.lins@bit3.de>
 * @author    Oliver Hoff <oliver@hofff.com>
 * @author    Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @copyright 2012-2015 Tristan Lins.
 * @copyright 2015-2016 Contao Community Alliance
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 * @link      https://github.com/bit3/contao-twig SCM
 * @link      http://de.contaowiki.org/Twig Wiki
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
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
// @codingStandardsIgnoreStart - class is not within a namespace - this will change with next major.
class ContaoTwigExtension extends Controller implements Twig_ExtensionInterface
// @codingStandardsIgnoreEnd
{
    /**
     * Create a new instance.
     *
     * @codingStandardsIgnoreStart - This override is not useless as we change the visibility.
     */
    public function __construct()
    {
        parent::__construct();
    }
    // @codingStandardsIgnoreEnd

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function initRuntime(Twig_Environment $environment)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeVisitors()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getOperators()
    {
        return array();
    }

    /**
     * {@inheritdoc}
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

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            'deserialize' => new Twig_Filter_Function('deserialize'),
            'standardize' => new Twig_Filter_Function('standardize'),
            'date'        => new Twig_Filter_Function(
                array(
                    $this,
                    '_dateFilter'
                ),
                array('needs_environment' => true)
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
                ),
                array('needs_environment' => true)
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
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
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'contao';
    }

    /**
     * Apply the date filter.
     *
     * @param \Twig_Environment          $env      The environment.
     *
     * @param DateInterval|DateTime|null $date     The date.
     *
     * @param null|string                $format   The format.
     *
     * @param null|string                $timezone The timezone.
     *
     * @return mixed|string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @codingStandardsIgnoreStart
     */
    public function _dateFilter(\Twig_Environment $env, $date, $format = null, $timezone = null) // @codingStandardsIgnoreEnd
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
     * @param int $timestamp The timestamp.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @codingStandardsIgnoreStart
     */
    public function _parseDateFilter($timestamp) // @codingStandardsIgnoreEnd
    {
        if ($timestamp instanceof \DateTime) {
            $timestamp = $timestamp->getTimestamp();
        }

        return \Date::parse($GLOBALS['TL_CONFIG']['dateFormat'], $timestamp);
    }

    /**
     * Parse the timestamp with the default date and time format.
     *
     * @param int $timestamp The timestamp.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @codingStandardsIgnoreStart
     */
    public function _parseDatimFilter($timestamp) // @codingStandardsIgnoreEnd
    {
        if ($timestamp instanceof \DateTime) {
            $timestamp = $timestamp->getTimestamp();
        }

        return \Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $timestamp);
    }

    /**
     * Prepare a database statement.
     *
     * @param string $sql The database statement.
     *
     * @return Statement
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @codingStandardsIgnoreStart
     */
    public function _prepareFilter($sql) // @codingStandardsIgnoreEnd
    {
        return Database::getInstance()
            ->prepare($sql);
    }

    /**
     * Set database statement update arguments.
     *
     * @param Statement $statement The statement.
     *
     * @param array     $arguments The arguments.
     *
     * @return Statement
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @codingStandardsIgnoreStart
     */
    public function _setFilter(Statement $statement, array $arguments) // @codingStandardsIgnoreEnd
    {
        return $statement->set($arguments);
    }

    /**
     * Execute a database statement.
     *
     * @param string|Statement $statement The statement.
     *
     * @param array            $arguments The arguments.
     *
     * @return Result
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @codingStandardsIgnoreStart
     */
    public function _executeFilter($statement,array $arguments = array()) // @codingStandardsIgnoreEnd
    {
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
     * @param string $statement The statement.
     *
     * @return Result
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @codingStandardsIgnoreStart
     */
    public function _queryFilter($statement) // @codingStandardsIgnoreEnd
    {
        return Database::getInstance()
            ->query($statement)
            ->fetchAllAssoc();
    }

    /**
     * Add an image.
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @codingStandardsIgnoreStart
     */
    public function _addImage() // @codingStandardsIgnoreEnd
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
     * @param array $arguments The arguments.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @codingStandardsIgnoreStart
     */
    public function _getMessages($arguments = array()) // @codingStandardsIgnoreEnd
    {
        return $this
            ->getMessages(
                isset($arguments[0]) ? $arguments[0] : false,
                isset($arguments[1]) ? $arguments[1] : false
            );
    }

    /**
     * Add and remove parameters from the current url.
     *
     * @param string|array $parameters      Array of parameters to set.
     *
     * @param bool         $addRefererId    Flag if the referer token shall be added to the url.
     *
     * @param array        $unsetParameters Array of parameters that shall be unset.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @codingStandardsIgnoreStart
     */
    public function _addToUrl($parameters, $addRefererId = true, $unsetParameters = array()) // @codingStandardsIgnoreEnd
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
     * Format with an array.
     *
     * @param string $format    The format string.
     *
     * @param array  $arguments The arguments.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @codingStandardsIgnoreStart
     */
    public function _vformat($format, $arguments) // @codingStandardsIgnoreEnd
    {
        return vsprintf($format, (array) $arguments);
    }

    /**
     * Generate a frontend url.
     *
     * @param mixed       $page     The page or page collection or page id.
     *
     * @param null|string $params   The url parameters to use.
     *
     * @param null|string $language The language string to use.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @codingStandardsIgnoreStart
     */
    public function _generateUrl($page, $params = null, $language = null) // @codingStandardsIgnoreEnd
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
     * @param \Twig_Environment $env   The twig environment.
     *
     * @param mixed             $value The value to convert to string.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @codingStandardsIgnoreStart
     */
    public function _stringify(\Twig_Environment $env, $value) //@codingStandardsIgnoreEnd
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
        if (null === $value) {
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
     * Retrieve the currently logged in frontend user.
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
     * Retrieve the currently logged in backend user.
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
     * Retrieve the currently active backend theme.
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
