<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2016 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * Komento activity class.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */
class PlgLogmanKomentoActivityKomento extends ComLogmanModelEntityActivity
{
    protected function _initialize(KObjectConfig $config)
    {
        $data = $config->data;

        $config->append(array(
            'format'       => '{actor} {action} {object.subtype} {object.type} {target.type} {target}',
            'object_table' => $data->package . '_' . KStringInflector::pluralize($data->name)
        ));

        parent::_initialize($config);
    }

    public function getPropertyImage()
    {
        $images = array(
            'publish'   => 'k-icon-circle-check',
            'unpublish' => 'k-icon-circle-x',
            'delete'    => 'icon-remove',
            'save'      => 'icon-comment'
        );

        // Default.
        $image = 'icon-task';

        if (in_array($this->verb, array_keys($images))) {
            $image = $images[$this->verb];
        }

        return $image;
    }

    protected function _actionConfig(KObjectConfig $config)
    {
        if ($this->getActivityVerb() == 'save') {
            $config->append(array('objectName' => 'added'));
        }

        parent::_actionConfig($config);
    }

    protected function _objectConfig(KObjectConfig $config)
    {
        $url = 'option=com_' . $this->package . '&view=' . $this->name . '&task=edit&c=comment&commentid=' . $this->row;

        $config->append(array(
            'id'         => $this->row,
            'objectName' => $this->title,
            'subtype'    => array('objectName' => 'Komento', 'object' => true),
            'type'       => array('url' => array('admin' => $url), 'find' => 'object')
        ));

        parent::_objectConfig($config);
    }

    public function getPropertyTarget()
    {
        $component = $this->getMetadata()->component;

        // Load the component translations for getting a translated the component name.
        $this->_loadTranslations($component);

        $target = $this->_getObject(array(
            'objectName' => 'item',
            'type'       => array(
                'object'     => true,
                'objectName' => $component,
                'url'        => array('admin' => 'option=' . $component),
                'find'       => 'component'
            )
        ));

        return $target;
    }

    protected function _findActivityComponent()
    {
        $component = JComponentHelper::getComponent($this->getMetadata()->component);

        return isset($component->id);
    }
}