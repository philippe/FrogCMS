<?php

/*
 * Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * The Comment plugin provides an interface to enable adding and moderating page comments.
 *
 * @package frog
 * @subpackage plugin.comment
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Bebliuc George <bebliuc.george@gmail.com>
 * @version 1.1.0
 * @since Frog version 0.9.3
 * @license http://www.gnu.org/licenses/agpl.html AGPL License
 * @copyright Philippe Archambault & Bebliuc George, 2008
 */
class Comment extends Record
{
    const TABLE_NAME = 'comment';
    const NONE = 0;
    const OPEN = 1;
    const CLOSED = 2;

    public static function find($args = null)
    {
        // Collect attributes...
        $where = isset($args['where']) ? trim($args['where']) : '';
        $order_by = isset($args['order']) ? trim($args['order']) :
            'is_approved, comment.created_on DESC';
        $offset = isset($args['offset']) ? (int)$args['offset'] : 0;
        $limit = isset($args['limit']) ? (int)$args['limit'] : 0;

        // Prepare query parts
        $where_string = empty($where) ? '' : "AND $where";
        $order_by_string = empty($order_by) ? '' : "ORDER BY $order_by";
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';

        $tablename = self::tableNameFromClassName('Comment');
        $tablename_page = self::tableNameFromClassName('Page');

        // Prepare SQL
        $sql = "SELECT comment.*, page.title AS page_title FROM $tablename AS comment, $tablename_page AS page " .
            "WHERE comment.page_id = page.id $where_string $order_by_string $limit_string";

        $stmt = self::$__CONN__->prepare($sql);
        $stmt->execute();

        // Run!
        if ($limit == 1) {
            return $stmt->fetchObject('Comment');
        } else {
            $objects = array();
            while ($object = $stmt->fetchObject('Comment'))
                $objects[] = $object;
			
            return $objects;
        }
    }

    public static function findAll($args = null)
    {
    	return self::find(array('limit' => 10));
    }

    public static function findById($id)
    {
        return self::find(array('where' => 'comment.id=' . (int)$id, 'limit' => 1));
    }

} // end Comment class
