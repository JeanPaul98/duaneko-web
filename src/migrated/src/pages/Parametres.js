import * as React from 'react';
import { View, Text } from 'react-native';
import { Button, Title } from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialIcons';
import { StatusBar } from 'expo-status-bar';

import { AuthContext } from '../composants/AuthContext';
import TopBarNav from '../composants/TopBarNav';
import { projectVersion } from '../composants/util';
import { TouchableOpacity } from 'react-native-gesture-handler';

let date1 = new Date().toLocaleDateString();
let time1 = new Date().toLocaleTimeString();

export default function Parametres({ navigation }) {
    const auth = React.useContext(AuthContext);
    return (
        <View style={{ flex: 1,}}>
            <StatusBar style="dark" />
            {/* TOP BAR */}
            <TopBarNav title ={'Paramètres'}/>
            <View style={{  height: 80,}}></View>
            {/* <View style={{ 
                height: 150,
                // width: "100%",
                display: 'flex',
                alignItems: 'flex-end',
                justifyContent: 'flex-start',
                flexDirection:'row',
                // borderColor:'rgba(100,100,100, 1)', 
                // borderWidth:2, 
                padding: 20,
                backgroundColor: '#f4f4f400',
            }}>
                <Title style={{fontSize: 30, color:'#222222', fontWeight:'bold'}}>Paramètres</Title>
            </View> */}
            <View style={{ display: 'flex', flexDirection: 'row', padding: 20,}}>
                <Text style={{ marginRight: 10}}>Compte : </Text>
                <Text>{(auth.loginState.user)}</Text>
            </View>
            <View style={{ display: 'flex', flexDirection: 'row', padding: 20,}}>
                <Text style={{ marginRight: 10}}>Accès : </Text>
                <Text>{'Admin'}</Text>
            </View>
            <View style={{borderWidth: 1, marginBottom: 20, width: '100%', borderColor: '#dddddd'}}>
                <TouchableOpacity title="Gestion des utilisateurs" onPress={() => { navigation.navigate("Gestion des utilisateurs") }} 
                    style={{ 
                        padding: 20, 
                        display: 'flex', 
                        flexDirection: 'row', 
                        alignItems: 'center', 
                        justifyContent: 'space-between',
                        width: '100%'
                    }}
                >
                    <Icon name="group" color={'#333333'} size={30} style={{marginRight: 15}} />
                    <Text>
                        Gestion des utilisateurs
                    </Text>
                    <Icon name="arrow-forward-ios" color={'#333333'} size={30} />
                </TouchableOpacity>
            </View>
            <View style={{borderRadius: 5, margin: 20, backgroundColor: 'crimson'}}>
                <TouchableOpacity title="Sign out" onPress={auth.signOut}
                    style={{ 
                        padding: 20, 
                        display: 'flex', 
                        alignItems: 'center', 
                        justifyContent: 'center'
                    }}
                >
                <Text style={{color: 'white'}}>
                    Déconnexion
                </Text>
                </TouchableOpacity>
            </View>
            <Text style={{fontSize: 16, paddingHorizontal: 20,}}>Version : {projectVersion}</Text>
            <Text style={{fontSize: 16, marginBottom: 150, paddingHorizontal: 20,}}>© Duaneko (2022)</Text>
        </View>
    );
}