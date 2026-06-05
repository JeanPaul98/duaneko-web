import * as React from 'react';
import { View, Text, Image, TouchableOpacity, KeyboardAvoidingView, TouchableWithoutFeedback, Keyboard } from 'react-native';
import { Title } from 'react-native-paper';
import { StatusBar } from 'expo-status-bar';
import { TextInput, ActivityIndicator  } from 'react-native-paper';

export function SignUpUserTelephoneDetailsScreen({ navigation }) {
    const [telephone, setTelephone] = React.useState('');
    const [erreurTel, setErreurTel] = React.useState('');

    return (
        <TouchableWithoutFeedback onPress={() => {Keyboard.dismiss();}}
            style={{
                flex: 1, 
                backgroundColor: '#f4f4f4'
                // borderWidth:1, borderColor:'white'
            }}>
            <View style={{
                flex: 1, 
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
                    // height: 80,
                    // width: "100%",
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'flex-start',
                    flexDirection:'row',
                    // borderColor:'rgba(100,100,100, 1)', borderWidth:1, 
                    paddingTop: 20,
                    paddingLeft: 20,
                    paddingBottom: 15,
                    backgroundColor: '#f4f4f4',
                }}>
                    <Title style={{fontSize: 30, color:'#222222', fontWeight:'bold'}}>Téléphone</Title>
                </View> */}


                <View style={{ 
                    // height: 80,
                    paddingTop: 40,
                    // width: "100%",
                    display: 'flex',
                    alignItems: 'flex-start',
                    justifyContent: 'flex-start',
                    flexDirection:'column',
                    // borderColor:'rgba(100,100,100, 1)', borderWidth:1, 
                    paddingHorizontal: 25,
                    backgroundColor: '#f4f4f400',
                }}>
                    <Text style={{fontSize: 14, color:'#444444', marginBottom: 10, textAlign: 'justify'}}>
                        Afin d'améliorer la fiabilité de l'application et de garantir l'authenticité de chaque signalement,
                        veuillez associer un numéro de téléphone à votre compte.
                    </Text>
                    {
                        (erreurTel !== '') ? 
                            <Text style={{display: 'flex' ,fontSize: 14, color:'#ff2222',  marginTop: 5, marginBottom: 5,}}>{erreurTel}</Text>
                        
                        : null
                    }
                </View>
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
                        label="Numéro de téléphone"
                        value={telephone}
                        onChangeText={text => setTelephone(text)}
                        // onFocus={() => setInputMargin(125)}
                        returnKeyType='next'

                        onSubmitEditing={() => (telephone !== '') ? navigation.navigate('SignUpUserVerificationCodeScreen', {tel: telephone}) : setErreurTel('Entrez une information valide pour continuer.')}
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
                            onPress={() => (telephone !== '') ? navigation.navigate('SignUpUserVerificationCodeScreen', {tel: telephone}) : setErreurTel('Entrez une information valide pour continuer.')}
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
            </View>
        </TouchableWithoutFeedback>
    );
}