import * as React from 'react';
import { View, Text, ImageBackground, Image, TouchableOpacity } from 'react-native';
import { Button, Title } from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import { StatusBar } from 'expo-status-bar';
import { projectVersion } from '../../composants/util';

import { AuthContext } from '../../composants/AuthContext';
import TopBarNav from '../../composants/TopBarNav';

export function ConnexionAcceuilScreen({ navigation }) {
    const { signOut } = React.useContext(AuthContext);

    
    return (
        <View style={{ flex: 1, backgroundColor: '#111111'}}>
            <StatusBar style="light" />
            <Text style={{ position: 'absolute', zIndex: 50, bottom: 0, right: 0, fontSize: 14, color:'#f4f4f4',}}>v.{projectVersion}</Text>
            <View style={{ 
                flex: 1,
                alignItems: 'center',
            }}>
                <Image style={{
                width: '100%', 
                height: '100%',
                opacity: 0.5,
                // borderColor: "rgba(66, 182, 64, 1)", borderWidth: 1,
                // borderRadius: 50
                }}
                source={require('../../assets/background-connexion-forest.jpg')} />
            </View>
            <View style={{ 
                width: '100%', 
                alignItems: 'center',
                position: 'absolute',
                top: 0
            }}>

                <View style={{ 
                    width: '100%', 
                    alignItems: 'center',
                    marginTop: 50,
                }}>
                    <Image style={{
                    width: 150, 
                    height: 150,
                    
                    // borderColor: "rgba(66, 182, 64, 1)", borderWidth: 1,
                    // borderRadius: 50
                    }}
                    source={require('../../assets/duaneko-logo-main-white.png')} />
                </View>

                <View style={{ 
                        flex: 1, display: 'flex',
                        // borderColor:'rgba(100,100,100, 1)', borderWidth:1, 
                        alignItems: 'center',
                        justifyContent: 'flex-start',
                        paddingTop: 30
                    }}>
                        <TouchableOpacity title="my location" mode='contained'
                            onPress={() => navigation.navigate('SignIn')}
                            style={{ 
                                width: 200,
                                height: 45, 
                                backgroundColor: '#3CAA4A',
                                display: 'flex',
                                justifyContent: 'center',
                                alignItems: 'center',
                                borderWidth:1, borderColor:'#3CAA4A', 
                                borderRadius: 10,
                                marginBottom: 25,
                                // height:'100%', width: '100%',
                            }} >
                            <Text style={{fontSize: 18, color:'#f4f4f4'}}>Connexion</Text>
                        </TouchableOpacity>
                        <TouchableOpacity title="my location" mode='contained'
                            onPress={() => navigation.navigate('SignUpStack')}
                            style={{ 
                                width: 200,
                                height: 45, 
                                backgroundColor: 'rgba(0,0,0, 0)',
                                display: 'flex',
                                justifyContent: 'center',
                                alignItems: 'center',
                                borderWidth:1, borderColor:'#f4f4f4', 
                                borderRadius: 10,
                                // height:'100%', width: '100%',
                            }} >
                            <Text style={{fontSize: 18, color:'#f4f4f4'}}>Inscription</Text>
                        </TouchableOpacity>
                </View>
            </View>

        </View>
    );
}