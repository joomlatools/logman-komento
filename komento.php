<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2016 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * Komento LOGman Plugin.
 *
 * Provides handlers for dealing with Komento events.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */
class PlgLogmanKomento extends ComLogmanPluginLogger
{
    /**
     * Keeps track of triggered events.
     *
     * @var array
     */
    protected $_triggered_events = array('publish' => array(), 'unpublish' => array(), 'delete' => array());

    /**
     * Overridden to handle Komento events only.
     */
    public function update(&$args)
    {
        $result = null;

        if (isset($args[2]))
        {
             $comment = $args[2];

            if (is_array($comment)) {
                $comment = current($comment);
            }

            if ($comment instanceof KomentoComment) {
                $result = parent::update($args);
            }
        }

        return $result;
    }

    /**
     * After save event handler.
     *
     * @param $component The comment component.
     * @param $cid       The component resource ID.
     * @param $comment   The comment object.
     */
    public function onAfterSaveComment($component, $cid, $comment)
    {
        $comment = current($comment);

        $this->log(array(
            'object' => array(
                'package'  => 'komento',
                'type'     => 'comment',
                'id'       => $comment->id,
                'name'     => $comment->name,
                'metadata' => array('component' => $component, 'cid' => $cid)
            ),
            'result' => 'saved',
            'verb'   => 'save',
            'actor'  => $comment->created_by
        ));
    }

    /**
     * After delete event handler.
     *
     * @param $component The comment component.
     * @param $cid       The component resource ID.
     * @param $comment   The comment object.
     */
    public function onAfterDeleteComment($component, $cid, $comment)
    {
        $comment = current($comment);

        if (!in_array($comment->id, $this->_triggered_events['delete']))
        {
            $this->log(array(
                'object' => array(
                    'package'  => 'komento',
                    'type'     => 'comment',
                    'id'       => $comment->id,
                    'name'     => $comment->name,
                    'metadata' => array('component' => $component, 'cid' => $cid)
                ),
                'result' => 'deleted',
                'verb'   => 'delete',
            ));

            $this->_triggered_events['delete'][] = $comment->id;
        }

    }

    /**
     * After publish event handler.
     *
     * @param $component The comment component.
     * @param $cid       The component resource ID.
     * @param $comment   The comment object.
     */
    public function onAfterPublishComment($component, $cid, $comment)
    {
        $comment = current($comment);

        if (!in_array($comment->id, $this->_triggered_events['publish']))
        {
            $this->log(array(
                'object' => array(
                    'package'  => 'komento',
                    'type'     => 'comment',
                    'id'       => $comment->id,
                    'name'     => $comment->name,
                    'metadata' => array('component' => $component, 'cid' => $cid)
                ),
                'result' => 'published',
                'verb'   => 'publish'
            ));

            $this->_triggered_events['published'][] = $comment->id;
        }

    }

    /**
     * After un-publish event handler.
     *
     * @param $component The comment component.
     * @param $cid       The component resource ID.
     * @param $comment   The comment object.
     */
    public function onAfterUnpublishComment($component, $cid, $comment)
    {
        $comment = current($comment);

        if (!in_array($comment->id, $this->_triggered_events['unpublish']))
        {
            $this->log(array(
                'object' => array(
                    'package'  => 'komento',
                    'type'     => 'comment',
                    'id'       => $comment->id,
                    'name'     => $comment->name,
                    'metadata' => array('component' => $component, 'cid' => $cid)
                ),
                'result' => 'unpublished',
                'verb'   => 'unpublish',
            ));

            $this->_triggered_events['unpublish'][] = $comment->id;
        }
    }
}