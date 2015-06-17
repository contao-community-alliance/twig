<?php

/**
 * Table tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['twig'] = '{title_legend},name,type;{html_legend},twig;{protected_legend:hide},protected;{expert_legend:hide},guests';
$GLOBALS['TL_DCA']['tl_module']['fields']['twig']   = array
(
    'label'       => &$GLOBALS['TL_LANG']['tl_module']['twig'],
    'exclude'     => true,
    'search'      => true,
    'inputType'   => 'textarea',
    'eval'        => array(
        'mandatory'  => true,
        'allowHtml'  => true,
        'class'      => 'monospace',
        'rte'        => 'ace|html',
        'helpwizard' => true
    ),
    'explanation' => 'insertTags',
    'sql'         => "mediumtext NULL"
);
