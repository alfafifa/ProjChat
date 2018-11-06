import React from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { BackHandler } from 'react-native';
import { StackNavigator, NavigationActions } from 'react-navigation';
import { addListener } from '../utils/redux';

//INTRO
import Intro from '../containers/Intro';

export const AppNavigation = StackNavigator({

    //INTRO
    IntroScreen: { screen: Intro },

}, {
        initialRouteName: 'IntroScreen',
        headerMode: 'none'
    }
);

class AppWithNavigationState extends React.Component {
    static propTypes = {
        dispatch: PropTypes.func.isRequired,
        nav: PropTypes.object.isRequired,
    };

    componentWillMount() {}

    componentDidMount() {
        BackHandler.addEventListener('hardwareBackPress', this.onBackButtonPressAndroid);
    }

    componentWillUnmount() {
        BackHandler.removeEventListener('hardwareBackPress', this.onBackButtonPressAndroid);
    }

    onBackButtonPressAndroid = () => {};

    render() {

        const { dispatch, nav } = this.props;

        return (
            <AppNavigation
                navigation={{
                    dispatch,
                    state: nav,
                    addListener,
                }}
            />
        );
    }
}


const mapStateToProps = state => ({
    nav: state.nav,
    Notif: state.Notif
});

export default connect(mapStateToProps)(AppWithNavigationState);