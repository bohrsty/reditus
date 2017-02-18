/*
 * This file is part of the Reditus project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

"use strict";

// import required modules
import React, {Component} from 'react';
import {AlertList} from 'react-bs-notifier';


/**
 * the main Component to layout the app
 */
class App extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
		
		// set initial state
		this.state = {
			alerts: []
		}
	}
	
	
	/**
	 * updates given parts of the state
	 * 
	 * @param state the state name to be updated
	 * @param value the value for state
	 */
	updateState(state, value) {
		
		var currentState = this.state;
		
		// check if state exists
		if(this.state[state] != undefined) {
			currentState[state] = value;
			this.setState(currentState);
		}
	}
	
	
	/**
	 * componentWillMount()
	 * executed directly before component will be mounted to DOM
	 */
	componentWillMount() {
		
	}
	
	
	/**
	 * getChildContext()
	 * register the context
	 */
	getChildContext() {
		return {addNotification: this.addNotification.bind(this)};
	}
	
	
	/**
	 * addNotification(params)
	 * given to context to add notifications
	 * 
	 * @param object params the parameter object for the notification
	 */
	addNotification(params) {
		
		// get current alerts
		var alerts = this.state.alerts;
		
		// add new alert
		alerts.unshift({
			id: (new Date()).getTime(),
			type: params.type,
			headline: params.headline,
			message: params.message
		});
		
		// update state
		this.updateState('alerts', alerts);
	}
	
	/**
	 * onDismissAlert(alert)
	 * handle the click on the dismiss button
	 * 
	 * @param object alert the dismissed alert object
	 */
	onDismissAlert(alert) {
		
		// get current alerts
		var alerts = this.state.alerts;

		// find the index of the alert that was dismissed
		var index = alerts.indexOf(alert);
		
		// remove the alert from array and update state
		if(index >= 0) {
			this.updateState('alerts', [...alerts.slice(0, index), ...alerts.slice(index + 1)]);
		}
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		// set title
		document.title = 'Reditus';
				
		return (
			<div>
				<AlertList
					position="top-right"
					alerts={this.state.alerts}
					dismissTitle="Schließen"
					onDismiss={this.onDismissAlert.bind(this)}
				/>
				{this.props.children}
			</div>
		);
	}
}


// set child context types
App.childContextTypes = {
	addNotification: React.PropTypes.func
};


//export
export default App;
