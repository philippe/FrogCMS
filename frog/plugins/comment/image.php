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
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @version 1.2.0
 * @since Frog version 0.9.3
 * @license http://www.gnu.org/licenses/agpl.html AGPL License
 * @copyright Philippe Archambault, Bebliuc George & Martijn van der Kleijn, 2008
 */
	session_start();
	$operators=array('+','-','*');
	$first_num=rand(1,5);
	$second_num=rand(6,11);
	shuffle($operators);
	$expression=$second_num.$operators[0].$first_num;
	eval("\$session_var=".$second_num.$operators[0].$first_num.";");

	$_SESSION['security_number']=$session_var;
	
	$img=imagecreatefromjpeg("test.jpg");

	$text_color		 = imagecolorallocate($img,255,255,255);
	
	$background_color= imagecolorallocate($img,255,255,255);
	

	
	imagefill($img,0,150,$background_color);
	imagettftext($img,rand(25,30),rand(-10,10),rand(10,30),rand(25,35),$background_color,"fonts/frog.ttf",$expression);

	header("Content-type:image/jpeg");
	header("Content-Disposition:inline ; filename=secure.jpg");
	imagejpeg($img);
?>