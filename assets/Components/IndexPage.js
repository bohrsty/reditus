/*
 * This file is part of the Tributum project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

"use strict";

// import required modules
import React, {Component} from 'react';
import {Jumbotron, Image} from 'react-bootstrap';


/**
 * Component for the index page of any route
 */
export default class IndexPage extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		switch(this.props.route.pageContent) {
			
			case 'homePage':
				return (
					<Jumbotron className="center">
						<h1>Tributum</h1>
					</Jumbotron>
				);
				break;
		}
	}
}
