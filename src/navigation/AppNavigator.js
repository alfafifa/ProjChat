import React, {Component} from 'react';
import PropTypes from 'prop-types';
import { StackNavigator, NavigationActions, addNavigationHelpers, createStackNavigator } from 'react-navigation';
import intro from "../containers/intro";
import tabNav from "./TabNavigator";

export const AppNavigator = StackNavigator({
    // UserCheckingScreen: { screen: UserChecking, navigationOptions: ({ navigation }) => {}},
    introScreen: { screen: intro },
    IndexScreen: { screen: tabNav },
}, {
    initialRouteName: 'introScreen',
    headerMode: 'none'
})


class AppWithNavigationState extends Component<{}> {
    render(){
        return(
            <AppNavigator />
        )
    }
}

export default AppWithNavigationState;