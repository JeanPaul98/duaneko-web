import * as React from 'react';
import { View, Text, ScrollView, TouchableOpacity, ActivityIndicator } from 'react-native';
import { Button, Title } from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import { StatusBar } from 'expo-status-bar';
import { serverAddress, socket } from '../composants/SocketContext';
import { AuthContext } from '../composants/AuthContext';
import TopBarNav from '../composants/TopBarNav';
import TopBarNavRetour from '../composants/TopBarNavRetour';

export default function GestionUtilisateurs({ navigation }) {
    const { loginState, setUsers } = React.useContext(AuthContext);
    React.useEffect(() => {
        socket.emit('getUtils','hello');
        // console.log('----- Displaying:\n------ - Home');
    }, []);

    return (
        <View style={{ flex: 1,}}>
            <StatusBar style="dark" />
            {/* TOP BAR */}
            <TopBarNavRetour fromRoute={'Paramètres'}/>
            <View style={{ 
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
                <Title style={{fontSize: 30, color:'#222222', fontWeight:'bold'}}>Utilisateurs</Title>
            </View>

            <ScrollView >
                {
                    loginState.users ? loginState.users.map(user => 
                        <View key={user.telephone} style={{borderBottomWidth: 1, borderBottomColor: '#aaaaaa', paddingVertical: 15, paddingHorizontal: 20}}>
                            <Text style={{fontSize: 16, fontWeight: '500'}}>{user.name}</Text>
                            <Text style={{fontSize: 14}}>Date inscrit : {new Date(user.createdAt).toLocaleString()}</Text>
                            <Text style={{fontSize: 14}}>Accès : {user.privilege}</Text>
                            <TouchableOpacity title="Menu" mode='contained' 
                                onPress={() => {
                                    console.log('pressed')
                                    fetch(serverAddress + "changerPrivilege", {
                                        method: 'POST',
                                        headers: {
                                        'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({"name": user.name, "privilege": 'developpeur'}) 
                                      })
                                      .then(response => response.json())
                                      .then(result => {
                                        console.log(result);
                                        socket.emit('getUtils','hello');
                                      })
                                      .catch(error => {
                                          console.log('loginHandle error', error);
                                      });
                                }}
                                style={{ 
                                    width: 150,
                                    height: 40, 
                                    backgroundColor: '#4fb155',
                                    borderRadius: 5,
                                    marginTop: 10,
                                    // borderColor:'rgba(100,100,100, 0)',  
                                    display: 'flex',
                                    justifyContent: 'center',
                                    alignItems: 'center',
                                    // marginLeft: 15, 
                                    // borderWidth:1, borderColor:'red', 
                                }} >
                                <Text style={{fontSize: 14, color: '#f4f4f4'}}>Modifier accès</Text>
                            </TouchableOpacity>
                        </View>
                        ) 
                        : <View style={{fontSize: 16 }}><ActivityIndicator/></View> 
                }
            </ScrollView>
        </View>
    );
}