/** @format */
// if upgrade react native to 0.57 dont forget to change babelrc to 
// "presets": ["module:metro-react-native-babel-preset"]

import React from 'react';
import {AppRegistry} from 'react-native';
// import App from './src';
import {name as appName} from './app.json';
import AppWithNavigationState from './src/navigation/TabNavigator';

const App = () => {
    return  <AppWithNavigationState />
}
export default App;

AppRegistry.registerComponent(appName, () => App);

