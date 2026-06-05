import * as React from 'react';
import { View, Text, Dimensions, Image, TouchableOpacity } from 'react-native';
import { Button, Title } from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import { StatusBar } from 'expo-status-bar';

import { AuthContext } from '../../composants/AuthContext';
import TopBarNav from '../../composants/TopBarNav';

export function PolitiqueConfidentialiteScreen({ navigation }) {
    const { signOut } = React.useContext(AuthContext);


    return (
        <View style={{ 
            flex: 1, 
            backgroundColor: '#f4f4f4',
            display: 'flex', justifyContent: 'center', alignItems: 'center',
            // borderColor:'rgba(100,100,100, 1)', borderWidth:5, 
        }}>
            <StatusBar style="dark" />
            <View style={{ 
                height: 80,
                marginTop: 20,
                // width: "100%",
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                flexDirection:'row',
                // borderColor:'rgba(100,100,100, 1)', borderWidth:1, 
                padding: 20,
                backgroundColor: '#f4f4f400',
            }}>
                <Title style={{fontSize: 20, color:'#222222'}}>Bienvenue sur </Title>
                <Title style={{fontSize: 20, color:'rgba(66, 182, 64, 1)'}}>Duaneko</Title>
            </View>

            <View style={{ 
                width: '100%', 
                alignItems: 'center',
            }}>
                <Image style={{
                    width: Dimensions.get('window').width * 0.8, 
                    height: Dimensions.get('window').width *0.8,
                    maxHeight: 500,
                    maxWidth: 500,
                    // width: 250, 
                    // height: 250,
                    // borderColor: "rgba(66, 182, 64, 1)", borderWidth: 1,
                    // borderRadius: 50
                }}
                resizeMode="contain"
                source={require('../../assets/splash.png')} />
            </View>
            <View style={{ 
                height: 80,
                width: "100%",
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                flexDirection:'row',
                flexWrap: 'wrap',
                // borderColor:'rgba(100,100,100, 1)', borderWidth:1, 
                padding: 20,
                backgroundColor: '#f4f4f400',
            }}>
                <Text style={{fontSize: 12, color:'#222222'}}>Veuillez lire notre 
                    <Text style={{fontSize: 12, color:'#1E90FF'}}> politique de confidentialité</Text>.
                </Text>
                <Text style={{fontSize: 12, color:'#222222', textAlign: 'center', marginTop: 5}}>Appuyez sur "Accepter et continuer" pour accepter les 
                    <Text style={{fontSize: 12, color:'#1E90FF'}}> conditions d'utilisation</Text>.
                </Text>
                
            </View>

            <TouchableOpacity title="my location" mode='contained'
                onPress={() => navigation.navigate('SignUpUserTelephoneDetails')}
                style={{ 
                    width: 250,
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
                <Text style={{fontSize: 18, color:'#f4f4f4'}}>Accepter et continuer</Text>
            </TouchableOpacity>
        </View>
    );
}