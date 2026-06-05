import * as React from 'react';
import { View, Text, ImageBackground, Image, TouchableOpacity, KeyboardAvoidingView } from 'react-native';
import { Button, Title } from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import { StatusBar } from 'expo-status-bar';

import { AuthContext } from '../../composants/AuthContext';
import TopBarNav from '../../composants/TopBarNav';
import { TextInput, ActivityIndicator  } from 'react-native-paper';

export function SignUpUserVerificationCodeScreen(props) {
    const { signOut } = React.useContext(AuthContext);
    const [ville, setVille] = React.useState('');
    const [nom, setNom] = React.useState('');



    return (
        <KeyboardAvoidingView behavior={Platform.OS === 'ios' ? 'padding' : 'height'} 
            style={{
                flex: 1, 
                backgroundColor: '#f4f4f4'
                // borderWidth:1, borderColor:'white'
            }}>

            <StatusBar style="light" />
            <View style={{ 
                width: '100%', 
                alignItems: 'center',
                backgroundColor: '#222222',
                borderBottomLeftRadius: 200,
                borderBottomRightRadius: 200,
                // marginTop: 50,
            }}>
                <Image style={{
                    width: '100%', 
                    height: 200,
                    opacity: 0.2,
                    borderBottomLeftRadius: 200,
                    borderBottomRightRadius: 200,
                    // borderColor: "rgba(66, 182, 64, 1)", borderWidth: 1,
                    // borderRadius: 50
                }}
                source={require('../../assets/background-inscription-top.jpg')} />
                <View style={{ 
                    width: '100%', 
                    alignItems: 'center',
                    marginTop: 30,
                    position: 'absolute',
                    top: 0,
                    left: 0,
                }}>
                    <Image style={{
                    width: 150, 
                    height: 150,
                    // borderColor: "rgba(66, 182, 64, 1)", borderWidth: 1,
                    // borderRadius: 50
                    }}
                    source={require('../../assets/duaneko-logo-main-white.png')} />
                </View>
            </View>

            {/* <View style={{ 
                height: 80,
                // width: "100%",
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'flex-start',
                flexDirection:'row',
                // borderColor:'rgba(100,100,100, 1)', borderWidth:1, 
                padding: 20,
                backgroundColor: '#f4f4f4',
            }}>
                <Title style={{fontSize: 30, color:'#222222', fontWeight:'bold'}}>Vérification</Title>
            </View> */}


            <View style={{ 
                // height: 80,
                // width: "100%",
                paddingTop: 40,
                display: 'flex',
                alignItems: 'flex-start',
                justifyContent: 'flex-start',
                flexDirection:'row',
                // borderColor:'rgba(100,100,100, 1)', borderWidth:1, 
                paddingHorizontal: 20,
                marginBottom: 20,
                backgroundColor: '#f4f4f400',
            }}>
                <Text style={{fontSize: 16, color:'#222222'}}>Un message texte a été envoyé sur votre numéro de téléphone. Entrez le code que vous recevrez.</Text>
            </View>
            <TextInput mode="outlined"
                style={{backgroundColor: '#f4f4f4', marginHorizontal: 20}}
                selectionColor='#aaaaaa'
                underlineColor='#999999'
                activeUnderlineColor='#9f9f9f'
                activeOutlineColor="rgba(60, 170, 74, 1)"
                label="Code de vérification"
                value={nom}
                onChangeText={text => setNom(text)}
                // onFocus={() => setInputMargin(125)}
                // returnKeyType='next'
                onSubmitEditing={() => {
                    // setInputMargin(150)
                }}
                theme={{ colors: { text: '#111111', placeholder:'#bbbbbb' } }}
            />
            <View style={{ 
                    flex: 1, display: 'flex',
                    // borderColor:'rgba(100,100,100, 1)', borderWidth:1, 
                    alignItems: 'center',
                    justifyContent: 'flex-start',
                    paddingTop: 30
                }}>
                    <TouchableOpacity title="my location" mode='contained'
                        onPress={() => props.navigation.navigate('SignUpUsernameScreen', {tel: props.route.params.tel}) }
                        style={{ 
                            width: 150,
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
                        <Text style={{fontSize: 18, color:'#f4f4f4'}}>Suivant</Text>
                    </TouchableOpacity>
            </View>

        </KeyboardAvoidingView>
    );
}