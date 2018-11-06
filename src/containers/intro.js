import React, { Component } from 'react';
import { StyleSheet } from 'react-native';
import AppIntroSlider from 'react-native-app-intro-slider';

const slides = [
  {
    key: 'somethun',
    title: 'Pesan dan Panggilan Gratis',
    text: 'Kirim pesan dan panggilan gratis dan aman',
    image: require('../assets/image/1.jpeg'),
    backgroundColor: '#59b2ab',
  },
  {
    key: 'somethun-dos',
    title: 'Penyimpanan Cadangan di Awan',
    text: 'Pesan anda tidak akan hilang meskipun berganti smartphone',
    image: require('../assets/image/2.jpeg'),
    backgroundColor: '#febe29',
  },
  {
    key: 'somethun1',
    title: 'Enkripsi Dua Arah',
    text: 'Hanya anda yang bisa membaca pesan',
    image: require('../assets/image/3.jpeg'),
    backgroundColor: '#22bcb5',
  }
];

export default class Intro extends Component {
    constructor(props) {
        super(props);
        this.state = {
            showRealApp: false
        }
    }

    static navigationOptions = { header: null };

    _onDone = () => {
      // User finished the introduction. Show real app through
      // navigation or simply by controlling state
      this.props.navigation.navigate('TopTabScreen');
    }
    
    render() {
      if (this.state.showRealApp) {
        return <Intro />;
      } else {
        return <AppIntroSlider slides={slides} onDone={this._onDone}/>;
      }
    }
  }

const styles = StyleSheet.create({
  image: {
    width: 320,
    height: 320,
  }
});