<?php

/**
 * Overrides the native ZF view helper to prepend the base URL for 
 * scripts to provide script paths.
 */
class Kizano_View_Helper_HeadScript extends Zend_View_Helper_HeadScript
{
    /**
     * Prefixes a given script path with a base URL for scripts before  
     * rendering the markup for a script element to include it.
     *
     * @param stdClass $item Object representing the script to render
     * @param string $indent
     * @param string $escapeStart
     * @param string $escapeEnd
     *
     * @return string Markup for the rendered script element
     */
    public function itemToString($item, $indent, $escapeStart, $escapeEnd)
    {
        if (Zend_Registry::isRegistered('config') &&
            isset($item->attributes['src']))
        {
        
            $config = Zend_Registry::get('config');
            $item->attributes['src'] = $config->filepath->script . '/' . ltrim($item->attributes['src'], '/');
        }
        return parent::itemToString($item, $indent, $escapeStart, $escapeEnd);
    }
}
