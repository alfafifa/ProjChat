import React, {Component} from 'react';
import { StatusBar, Text, View } from 'react-native';
import { createStackNavigator, createMaterialTopTabNavigator } from 'react-navigation';
import { collapsibleOptionsForTab, collapsibleTabConfig } from 'react-navigation-collapsible';
import Icon from 'react-native-vector-icons/Ionicons';

import {MessageScreen, HistoryScreen, PaymentScreen, ContactScreen, MessageDetailScreen, OTGScreen} from '../containers/Screen';
import intro from "../containers/intro";

const backgroundColor = '#061';

export default class App extends Component{
  render(){
    return (
      [
        <StackNavigator key='navigator'/>,
      ]
    )
  }
}


const TopTabNavigator = createMaterialTopTabNavigator(
  {
    Screen1: { screen: MessageScreen },
    Screen2: { screen: HistoryScreen },
    Screen3: { screen: ContactScreen },
    Screen4: { screen: PaymentScreen },
  },
  collapsibleTabConfig({
    animationEnabled: true,
    navigationOptions:{
      tabBarOptions: {
        indicatorStyle: { backgroundColor: 'white' },
        style: { borderTopColor: 'transparent', borderTopWidth: 0, elevation: 0, backgroundColor: backgroundColor },
      }
    }
  })
);


const routeConfig = {
  introScreen: { screen: intro },
  TopTabScreen: { 
    screen: TopTabNavigator, 
    navigationOptions: props => collapsibleOptionsForTab(props, {title: ''}) },
  detailMessageScreen: { screen: MessageDetailScreen },
  VerifyPhone: {screen: OTGScreen}
};

const navigatorConfig = {
  navigationOptions: {
    headerStyle: { backgroundColor: backgroundColor, borderBottomColor: 'transparent', borderBottomWidth: 0, elevation: 0 },
    headerTitleStyle: { color: 'white' },
    headerTintColor: 'white',
    headerLeft: (<Text style={{color:'#fff', marginLeft:16, fontSize:18, fontWeight:'800'}}>Blink</Text>),
    headerRight: (
      <View style={{flexDirection:'row'}}>
          <Icon name="ios-search" size={22} style={{ color: '#fff', marginHorizontal:14 }} />
          <Icon name="md-more" size={22} style={{ color: '#fff', marginHorizontal:16 }} />
      </View>
    )
  },
  initialRouteName: 'VerifyPhone', 
};

const StackNavigator = createStackNavigator(routeConfig, navigatorConfig);

