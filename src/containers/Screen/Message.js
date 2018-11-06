import React, {Component} from 'react';
import { Text, FlatList, Animated, TouchableOpacity, View, Image, Dimensions } from 'react-native';

import { withCollapsible } from 'react-navigation-collapsible';

const { width, height } = Dimensions.get('window');
const AnimatedFlatList = Animated.createAnimatedComponent(FlatList);

class Message extends Component{
  static navigationOptions = {
    title: 'Message'
  };

  constructor(props){
    super(props);

    const data = [];
    for(let i = 0 ; i < 60 ; i++){
      data.push(i);
    }

    this.state = {
      data: data
    }
  }

  renderItem = ({item}) => (
    <TouchableOpacity 
      onPress={() => {this.props.navigation.navigate('detailMessageScreen')}}>
        <View style={{ flex: 1, flexDirection: 'row', backgroundColor: '#fff', margin:10 }}>
            <Image
                style={{ marginTop: 1.5, width: 50, height: 50, borderRadius: 25 }}
                source={{ uri: "https://placeimg.com/140/140/any" }}
                resizeMode="cover"
            />
            <View style={{ flexDirection: 'column', marginLeft:5, marginTop:5, borderColor: '#eee', borderBottomWidth: 0.5,}}>
                <Text style={{ marginLeft: 10, color: '#000', width: width * .7, backgroundColor: 'transparent', fontWeight: "600", fontSize: 16, justifyContent: 'flex-start' }} numberOfLines={1} >Andi Tampubolon</Text>
                <Text style={{ marginLeft: 10, color: "rgba(0,0,0,.3)", width: width * .7, height: 30, backgroundColor: 'transparent', fontSize: 14, marginBottom: 5, marginTop: 3 }} numberOfLines={4} >Ucing</Text>
            </View>
            <View style={{ position:'absolute', right:0, top:8, flexDirection:'column'}}>
                <Text style={{ marginLeft: 10, color: 'green', backgroundColor: 'transparent', fontWeight: "600", fontSize: 10, justifyContent: 'flex-start' }} numberOfLines={1} >10.20</Text>                        
                <View style={{height:20, width:20, borderRadius: 10, backgroundColor:'green', marginLeft:12, marginTop:7, alignItems:'center'}}>
                    <Text style={{fontSize:10, color:'#fff', marginTop:3.5}}>10</Text>
                </View>
            </View>
        </View>
    </TouchableOpacity>
  )

  // onScroll = e => {
  //   console.log(e.nativeEvent.contentOffset);
  // }

  render(){
    const { paddingHeight, scrollY, onScroll } = this.props.collapsible;

    return (
      <AnimatedFlatList 
        style={{flex: 1, backgroundColor:'#fff'}}
        data={this.state.data}
        renderItem={this.renderItem}
        keyExtractor={(item, index) => String(index)}
        contentContainerStyle={{paddingTop: paddingHeight}}
        scrollIndicatorInsets={{top: paddingHeight}}
        onScroll={onScroll}
        _mustAddThis={scrollY}
        />
    )
  }
}

export default withCollapsible(Message, {iOSCollapsedColor: '#031'});
