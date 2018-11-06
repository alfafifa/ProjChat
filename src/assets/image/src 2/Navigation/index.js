import React from 'react';
import { BackHandler } from 'react-native';
import { StackNavigator } from 'react-navigation';
import Dashboard from '../Screen/Dashboard';
import Chat from '../Screen/Chat';

export const AppNavigator = StackNavigator({
    Home: { screen: Dashboard },
    ChatScreen: { screen: Chat}, 
}, {
    headerMode: 'none',
    navigationOptions: {
      headerVisible: false,
    }
})

class AppWithNavigationState extends React.Component {
  
    render() {
  
      const { dispatch, nav } = this.props;
      return (
        <AppNavigator />
      );
    }
  }
  
  
  const mapStateToProps = state => ({
    nav: state.nav,
    Notif: state.Notif
  });
  
  export default AppWithNavigationState