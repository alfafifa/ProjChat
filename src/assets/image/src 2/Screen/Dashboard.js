import React, { Component } from 'react';
import {
    View,
    Text,
    StyleSheet,
    ScrollView, 
    FlatList, TouchableOpacity,
    Image, Dimensions
} from 'react-native';
import Tab from '../Navigation/tab'
import Icon from 'react-native-vector-icons/Ionicons';
import ActionButton from 'react-native-action-button';


const { width, height } = Dimensions.get('window');

const list = [
  {
    id:1,
    name: 'Amy Farha',
    avatar_url: 'https://s3.amazonaws.com/uifaces/faces/twitter/ladylexy/128.jpg',
    subtitle: 'Ini hanya prototype'
  },
  {
    id: 2,
    name: 'Chris Jackson',
    avatar_url: 'https://s3.amazonaws.com/uifaces/faces/twitter/adhamdannaway/128.jpg',
    subtitle: 'Saya Lagi pergi ke Jakarta dulu'
  },
  {
    id: 3,
    name: 'Jackson',
    avatar_url: 'https://s3.amazonaws.com/uifaces/faces/twitter/adhamdannaway/128.jpg',
    subtitle: 'Saya Lagi pergi ke Bandung dulu'
  },
  {
    id: 4,
    name: 'Chris',
    avatar_url: 'https://s3.amazonaws.com/uifaces/faces/twitter/adhamdannaway/128.jpg',
    subtitle: 'Saya Lagi pergi '
  },
  {
    id: 5,
    name: 'Haris Jackson',
    avatar_url: 'https://s3.amazonaws.com/uifaces/faces/twitter/adhamdannaway/128.jpg',
    subtitle: 'Jakarta dulu'
  },
  {
    id: 2,
    name: 'Chris Jackson',
    avatar_url: 'https://s3.amazonaws.com/uifaces/faces/twitter/adhamdannaway/128.jpg',
    subtitle: 'Saya Lagi pergi ke Jakarta dulu'
  },
  {
    id: 2,
    name: 'Chris Jackson',
    avatar_url: 'https://s3.amazonaws.com/uifaces/faces/twitter/adhamdannaway/128.jpg',
    subtitle: 'Saya Lagi pergi ke Jakarta dulu'
  },
  {
    id: 2,
    name: 'Chris Jackson',
    avatar_url: 'https://s3.amazonaws.com/uifaces/faces/twitter/adhamdannaway/128.jpg',
    subtitle: 'Saya Lagi pergi ke Jakarta dulu'
  }
]

class home extends Component {
    constructor(props) {
        super(props);
        this.state = {
            currentTab: 0,
        };
    }

    componentDidMount(){
        this.TabCalls(),
        this.TabChat(),
        this.TabInbox(),
        this.TabPayment()
    }

    TabChat(){
        return(
            <FlatList
                data={list}
                numColumns={1}
                renderItem={({ item }) =>
                <View key={"keys"} style={{paddingLeft: 7, paddingRight: 7, backgroundColor: '#FFF'}}>
                    <TouchableOpacity onPress={() => this.props.navigation.navigate('ChatScreen')}>
                        <View style={{ flex: 1, flexDirection: 'row', padding: 5, backgroundColor: '#fff' }}>
                            <Image
                                style={{ marginTop: 1.5, width: 60, height: 60, borderRadius: 30 }}
                                source={{ uri: item.avatar_url }}
                                resizeMode="cover"
                            />
                            <View style={{ flexDirection: 'column', marginLeft:10, borderColor: '#eee', borderBottomWidth: 0.5,}}>
                                <Text style={{ marginLeft: 10, color: '#000', width: width * .7, backgroundColor: 'transparent', fontWeight: "600", fontSize: 16, justifyContent: 'flex-start' }} numberOfLines={1} >{item.name}</Text>
                                <Text style={{ marginLeft: 10, color: "rgba(0,0,0,.3)", width: width * .7, height: 30, backgroundColor: 'transparent', fontSize: 14, marginBottom: 5, marginTop: 3 }} numberOfLines={4} >{item.subtitle}</Text>
                            </View>
                            <View style={{ position:'absolute', right:0, top:10, flexDirection:'column'}}>
                                <Text style={{ marginLeft: 10, color: 'green', backgroundColor: 'transparent', fontWeight: "600", fontSize: 10, justifyContent: 'flex-start' }} numberOfLines={1} >10.20</Text>                        
                                <View style={{height:20, width:20, borderRadius: 10, backgroundColor:'green', marginLeft:12, marginTop:3}}>
                                    <Text style={{fontSize:10, color:'#fff', marginLeft:7, marginTop:2}}>{item.id}</Text>
                                </View>
                            </View>
                        </View>
                    </TouchableOpacity>
                </View>
                }
                key={'ONE COLUMN'}
                keyExtractor={(item, index) => index.toString()}
                bounces={false}
            />
        )
    }

    TabCalls(){
        return(
            <FlatList
                data={list}
                numColumns={1}
                renderItem={({ item }) =>
                <View key={"keys"} style={{paddingLeft: 7, paddingRight: 7, backgroundColor: '#FFF'}}>
                    <TouchableOpacity onPress={() => console.log("aabbbcc")}>
                        <View style={{ flex: 1, flexDirection: 'row', padding: 5, backgroundColor: '#fff' }}>
                            <Image
                                style={{ marginTop: 1.5, width: 60, height: 60, borderRadius: 30 }}
                                source={{ uri: item.avatar_url }}
                                resizeMode="cover"
                            />
                            <View style={{ flexDirection: 'column', marginLeft:10, borderColor: '#eee', borderBottomWidth: 0.5,}}>
                                <Text style={{ marginLeft: 10, color: '#000', width: width * .7, backgroundColor: 'transparent', fontWeight: "600", fontSize: 16, justifyContent: 'flex-start' }} numberOfLines={1} >{item.name}</Text>
                                <Text style={{ marginLeft: 10, color: "rgba(0,0,0,.3)", width: width * .7, height: 30, backgroundColor: 'transparent', fontSize: 14, marginBottom: 5, marginTop: 3 }} numberOfLines={4} >(12) Yesterday, 21,12</Text>
                            </View>
                            <View style={{ position:'absolute', right:0, top:20}}>
                                <Icon name="ios-videocam" color='green' size={23} style={{ padding:5, marginRight:10 }} />                        
                            </View>
                        </View>
                    </TouchableOpacity>
                </View>
                }
                key={'ONE COLUMN'}
                keyExtractor={(item, index) => index.toString()}
                bounces={false}
            />
        )
    }

    TabPayment(){
        return(
            <View style={{flex:1}}>
            <FlatList
                data={list}
                numColumns={1}
                renderItem={({ item }) =>
                <View key={"keys"} style={{paddingLeft: 7, paddingRight: 7, backgroundColor: '#FFF'}}>
                    <TouchableOpacity onPress={() => console.log("aabbbcc")}>
                        <View style={{ flex: 1, flexDirection: 'row', padding: 5, backgroundColor: '#fff' }}>
                            <Image
                                style={{ marginTop: 1.5, width: 60, height: 60, borderRadius: 30 }}
                                source={{ uri: item.avatar_url }}
                                resizeMode="cover"
                            />
                            <View style={{ flexDirection: 'column', marginLeft:10, borderColor: '#eee', borderBottomWidth: 0.5,}}>
                                <Text style={{ marginLeft: 10, color: '#000', width: width * .7, backgroundColor: 'transparent', fontWeight: "600", fontSize: 16, justifyContent: 'flex-start' }} numberOfLines={1} >{item.name}</Text>
                                <Text style={{ marginLeft: 10, color: "#fff", width: width * .7, height: 30, backgroundColor: 'yellow', fontSize: 14, marginBottom: 5, marginTop: 3, borderRadius:3 }} numberOfLines={4} >Pending</Text>
                            </View>
                        </View>
                    </TouchableOpacity>
                </View>
                }
                key={'ONE COLUMN'}
                keyExtractor={(item, index) => index.toString()}
                bounces={false}
            />
            </View>
        )
    }

    TabInbox(){
        return(
            <View style={{flex:1}}>
            <FlatList
                data={list}
                numColumns={1}
                renderItem={({ item }) =>
                <View key={"keys"} style={{paddingLeft: 7, paddingRight: 7, backgroundColor: '#FFF'}}>
                    <TouchableOpacity onPress={() => console.log("aabbbcc")}>
                        <View style={{ flex: 1, flexDirection: 'row', padding: 5, backgroundColor: '#fff' }}>
                            <Image
                                style={{ marginTop: 1.5, width: 60, height: 60, borderRadius: 30 }}
                                source={{ uri: item.avatar_url }}
                                resizeMode="cover"
                            />
                            <View style={{ flexDirection: 'column', marginLeft:10, borderColor: '#eee', borderBottomWidth: 0.5,}}>
                                <Text style={{ marginLeft: 10, color: '#000', width: width * .7, backgroundColor: 'transparent', fontWeight: "600", fontSize: 16, justifyContent: 'flex-start' }} numberOfLines={1} >{item.name}</Text>
                                <View style={{backgroundColor:'rgba(0,0,0,.2)', width:100, padding:5, flexDirection:'row', borderRadius:5, alignItems:'center', alignContent:'center'}}>
                                    <Text style={{fontSize:14, marginLeft:20, color:'#fff'}}>Pending</Text>
                                </View>
                            </View>
                        </View>
                    </TouchableOpacity>
                </View>
                }
                key={'ONE COLUMN'}
                keyExtractor={(item, index) => index.toString()}
                bounces={false}
            />
            </View>
        )
    }
    
    handleTabChange = (value) => this.setState({ currentTab: value });

    render() {

        const DATA = [this.TabChat(), this.TabInbox(), this.TabCalls(), this.TabChat()];
        
        return (
            <View>
            <ScrollView 
                stickyHeaderIndices={[1]}
                showsVerticalScrollIndicator={false}
            >
                <View style ={styles.topBit}>
                    <Text style={styles.logo}>WhatsApp Fake</Text>
                    <View style={styles.row}>
                        <Icon name="ios-search" color='#fff' size={23} style={{ padding:5, marginRight:10 }} />
                        <Icon name="md-more" color='#fff' size={23} style={{ padding:5 }} />
                    </View>
                </View>
                <Tab
                    tabTitles={['Chat', 'Payment', 'Calls']}
                    onChangeTab={this.handleTabChange} 
                    containerStyle={{backgroundColor: '#075e54', borderBottomWidth: 0.5, borderColor: '#fff'}}
                />
                <View style={[styles.container]}>
                    {DATA[this.state.currentTab]}
                </View>
            </ScrollView>
            <View style={{height:50, width:50, borderRadius:25, alignItems:'center', position:'absolute', bottom:20, right:20, backgroundColor:'green', alignContent:'center', justifyContent:'center', elevation:2}}>
                <Icon name="md-create" color='#fff' size={23} style={{ padding:5 }} />
            </View>
            </View>
        );
    }
}

const styles = StyleSheet.create({
    container: {
      flex: 1,
      justifyContent: 'center',
      alignItems: 'center',
      backgroundColor: '#F5FCFF',
    },
    actionButtonIcon: {
        fontSize: 20,
        height: 22,
        color: 'white',
      },
    welcome: {
      fontSize: 20,
      textAlign: 'center',
      margin: 10,
    },
    logo:{
      color:'#fff',
      fontSize:16,
      margin:10,
      marginLeft:20,
      fontWeight:'500',
    },
    row:{
      flexDirection:'row',
      marginRight:10,
    },
    topBit:{
      flexDirection:'row',
      alignItems:'center',
      paddingTop:15,
      backgroundColor:'#075e54',
      justifyContent:'space-between'
    },
  });

export default home