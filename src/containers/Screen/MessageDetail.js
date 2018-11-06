import React from 'react';
import {
  Platform,
  StyleSheet,
  Text,
  View,
  Image,
  TouchableOpacity,
  Dimensions,
  SafeAreaView,
} from 'react-native';
import { GiftedChat } from 'react-native-gifted-chat';
import Icon from 'react-native-vector-icons/Ionicons';

const { width, height } = Dimensions.get('window');

export default class MessageDetail extends React.Component {
    constructor(props) {
      super(props);
      this.state = {
        messages: [],
      };
    }

    static navigationOptions = { header: null };
  
    onSend(messages = []) {
      this.setState(previousState => ({
        messages: GiftedChat.append(previousState.messages, messages),
      }))
    }
    
    componentWillMount() {
      this.setState({
        messages: [
          {
            _id: 1,
            text: 'Hello developer',
            createdAt: new Date(),
            user: {
              _id: 2,
              name: 'React Native',
              avatar: 'https://placeimg.com/140/140/any',
            },
          },
        ],
      })
    }

    componentWillUnmount() {
      this._isMounted = false;
    }
  
    render() {
      return (
        <SafeAreaView style={{flex:1}}>
          <View style={{height:50, flexDirection:'row', backgroundColor:'#061', padding:5, alignItems:'center'}}>
            <TouchableOpacity onPress={() => this.props.navigation.goBack()}>
              <Icon name="md-arrow-back" color='#fff' size={22} style={{ padding:5, marginRight:10 }} />   
            </TouchableOpacity>
            <Image
              style={{ marginTop: 1.5, width: 40, height: 40, borderRadius: 20 }}
              source={{ uri: 'https://s3.amazonaws.com/uifaces/faces/twitter/adhamdannaway/128.jpg' }}
              resizeMode="cover"
            />
            <View style={{flexDirection:'column'}}>
                <Text style={{ marginLeft: 10, color: '#fff', width: width * .7, backgroundColor: 'transparent', fontWeight: "600", fontSize: 16, justifyContent: 'flex-start' }} numberOfLines={1} >Amy Farha</Text>
                <Text style={{ marginLeft: 10, color: '#fff', width: width * .7, backgroundColor: 'transparent', fontWeight: "600", fontSize: 12, justifyContent: 'flex-start' }} numberOfLines={1} >Last seen today at 14.18</Text>
            </View>
          </View>
          <GiftedChat
            messages={this.state.messages}
            onSend={messages => this.onSend(messages)}
            user={{
              _id: 1,
            }}
          />
        </SafeAreaView>
      )
    }
  }
  
  const styles = StyleSheet.create({
    footerContainer: {
      marginTop: 5,
      marginLeft: 10,
      marginRight: 10,
      marginBottom: 10,
    },
    footerText: {
      fontSize: 14,
      color: '#aaa',
    },
  });
