import * as React from 'react';
import { View, Text } from 'react-native';
import { Button, Title } from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import { StatusBar } from 'expo-status-bar';

import { AuthContext } from '../composants/AuthContext';
import TopBarNav from '../composants/TopBarNav';

export default function Messages({ navigation }) {
    const { signOut, loginState } = React.useContext(AuthContext);


    return (
        <View style={{ flex: 1,}}>
            <StatusBar style="dark" />
            {/* TOP BAR */}
            <TopBarNav title={'Messages'}/>
            <View style={{  height: 80,}}></View>
            {/* <View style={{ 
                height: 150,
                // width: "100%",
                display: 'flex',
                alignItems: 'flex-end',
                justifyContent: 'flex-start',
                flexDirection:'row',
                // borderColor:'rgba(100,100,100, 1)', borderWidth:2, 
                padding: 20,
                backgroundColor: '#f4f4f400',
            }}>
                <Title style={{fontSize: 30, color:'#222222', fontWeight:'bold'}}>Messages</Title>
            </View> */}
            {
                loginState.chatMsg !== null ? <View>
                   <Text style={{padding: 20, fontSize: 16}}>{loginState.chatMsg}</Text>
                </View> :
                <View style={{ 
                        flex: 1,display: 'flex',
                        // borderColor:'rgba(100,100,100, 1)', borderWidth:1, 
                        alignItems: 'center',
                        justifyContent: 'center',
                    }}>
                    <Text style={{fontSize: 16, marginBottom: 150}}>Pas de messages</Text>
                </View>

            }

        </View>
    );
}