<?php

/**
 * Table tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['twig'] = '{type_legend},type;{text_legend},twig;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['fields']['twig'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['twig'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'textarea',
	'eval'                    => array('mandatory'=>true, 'allowHtml'=>true, 'class'=>'monospace', 'rte'=>'ace|html', 'helpwizard'=>true),
	'explanation'             => 'insertTags',
	'sql'                     => "mediumtext NULL"
);
