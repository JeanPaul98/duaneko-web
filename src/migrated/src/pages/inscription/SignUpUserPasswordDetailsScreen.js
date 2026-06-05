import * as React from 'react';
import { View, Text, ImageBackground, Image, TouchableOpacity, KeyboardAvoidingView, TouchableWithoutFeedback, Keyboard } from 'react-native';
import { Button, Title } from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import { StatusBar } from 'expo-status-bar';

import { AuthContext } from '../../composants/AuthContext';
import TopBarNav from '../../composants/TopBarNav';
import { TextInput, ActivityIndicator  } from 'react-native-paper';

export function SignUpUserPasswordDetailsScreen(props) {
    const { signUp } = React.useContext(AuthContext);
    const [password, setPassword] = React.useState('');
    const [password2, setPassword2] = React.useState('');
    
    React.useEffect(() => {
        // console.log('----- Displaying:\n------ - Home');

    });

    return (
        <TouchableWithoutFeedback onPress={() => {Keyboard.dismiss();}}
            style={{
                flex: 1, 
                backgroundColor: '#f4f4f4'
                // borderWidth:1, borderColor:'white'
            }}>
            <View style={{
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
                    <Title style={{fontSize: 30, color:'#222222', fontWeight:'bold'}}>Mot de passe</Title>
                </View> */}


                <View style={{ 
                    // height: 80,
                    // width: "100%",
                    display: 'flex',
                    alignItems: 'flex-start',
                    justifyContent: 'flex-start',
                    flexDirection:'row',
                    // borderColor:'rgba(100,100,100, 1)', borderWidth:1, 
                    paddingHorizontal: 20,
                    paddingTop: 40,
                    marginBottom: 20,
                    backgroundColor: '#f4f4f400',
                }}>
                    <Text style={{fontSize: 16, color:'#222222'}}>Veuillez définir un mot de passe pour votre compte.</Text>
                </View>

                {/* DEBUG */}
                {/* <Text style={{fontSize: 16, color:'#222222'}}>{JSON.stringify(props.route.params) + " | " + password}</Text> */}
                
                <KeyboardAvoidingView 
                    behavior={Platform.OS === 'ios' ? 'position' : 'position'}
                    style={{ 
                        // flex: 1, display: 'flex',
                        // borderColor:'rgba(100,100,100, 1)', borderWidth: 5, 
                        // alignItems: 'center',
                        // justifyContent: 'flex-start',
                        // paddingTop: 30
                    }}>
                    <TextInput mode="outlined"
                        style={{backgroundColor: '#f4f4f4', marginHorizontal: 20}}
                        selectionColor='#aaaaaa'
                        underlineColor='#999999'
                        activeUnderlineColor='#9f9f9f'
                        activeOutlineColor="rgba(60, 170, 74, 1)"
                        label="Mot de passe"
                        value={password}
                        onChangeText={text => setPassword(text)}
                        // onFocus={() => setInputMargin(125)}
                        // returnKeyType='next'
                        onSubmitEditing={() => {
                            // setInputMargin(150)
                        }}
                        theme={{ colors: { text: '#111111', placeholder:'#bbbbbb' } }}
                    />
                    <TextInput mode="outlined"
                        style={{backgroundColor: '#f4f4f4', marginHorizontal: 20}}
                        selectionColor='#aaaaaa'
                        underlineColor='#999999'
                        activeUnderlineColor='#9f9f9f'
                        activeOutlineColor="rgba(60, 170, 74, 1)"
                        label="Confirmation mot de passe"
                        value={password2}
                        onChangeText={text => setPassword2(text)}
                        // onFocus={() => setInputMargin(125)}
                        // returnKeyType='next'
                        onSubmitEditing={() => {
                            // setInputMargin(150)
                        }}
                        theme={{ colors: { text: '#111111', placeholder:'#bbbbbb' } }}
                    />
                </KeyboardAvoidingView>
                <View style={{ 
                        flex: 1, display: 'flex',
                        // borderColor:'rgba(100,100,100, 1)', borderWidth:1, 
                        alignItems: 'center',
                        justifyContent: 'flex-start',
                        paddingTop: 30
                    }}>
                        <TouchableOpacity title="my location" mode='contained'
                            onPress={() => (password !== '') ? signUp({tel: props.route.params.tel, username: props.route.params.username, password: password, dateJoined: new Date().toISOString()}) : null }
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
                            <Text style={{fontSize: 18, color:'#f4f4f4'}}>Suivant</Text>
                        </TouchableOpacity>
                </View>
            </View>
        </TouchableWithoutFeedback>
    );
}