
import * as React from 'react';
import { 
    Dimensions, 
    StyleSheet, 
    Image, 
    View, 
    Alert,
    Text, 
    KeyboardAvoidingView, 
    TouchableWithoutFeedback,
    Keyboard
} from 'react-native';
 import { StatusBar } from 'expo-status-bar';
 import { TextInput, Button, Switch, ActivityIndicator  } from 'react-native-paper';
  
import { AuthContext } from '../../composants/AuthContext';
import { serverAddress } from '../../composants/SocketContext';

const ScreenHeight = Dimensions.get("window").height;
const ScreenWidth = Dimensions.get("window").width;

export function Connexion() {
    const [username, setUsername] = React.useState('');
    const [password, setPassword] = React.useState('');
  
    const { signIn } = React.useContext(AuthContext);
    const [isSwitchOn, setIsSwitchOn] = React.useState(false);
    const [activityIndicatorState, setActivityIndicatorState] = React.useState(false);
    const [inputMargin, setInputMargin] = React.useState(150);

    const onToggleSwitch = () => setIsSwitchOn(!isSwitchOn);

    return (
      <View style={styles.container}>
        <StatusBar style="light" />
        <TouchableWithoutFeedback onPress={() => {setInputMargin(150); Keyboard.dismiss();}}>
        <View style={styles.container}>

        <View style={{...styles.logo, height: inputMargin, position: 'relative', top: 0}}>
          <View style={{display:'flex', 
            // borderWidth:1, borderColor:'blue',
            alignItems:'flex-start', paddingLeft: 20,}}>
            <Image style={{
              resizeMode: "cover",
              // borderWidth:1, borderColor:'red',
              opacity: 0.7,
              marginTop: 50,
              
              height:50,
              width: 75,     
            }}                          
            source={require('../../assets/logo.jpg')} />
          </View>
          <View style={{position: 'absolute', top: 10, left: 20,
            // borderWidth:1, borderColor:'blue',
            // alignItems:'flex-start',
            paddingLeft: 20,}}>
            <Text style={{
                // borderWidth:1, borderColor:'red',
                fontSize: 40,
                fontWeight: 'bold',
                color: '#cccccc',
                marginTop: 50,
                height:50,
                width: 250,     
              }}
            >
              duaneko
            </Text>
          </View>
        </View>

        <KeyboardAvoidingView style={{
            flex: 1,  
            // borderWidth:1, borderColor:'white'
        }}
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'} >

        <TextInput
            style={{backgroundColor: '#2b2b2b', marginBottom: 20, marginHorizontal: 20}}
            selectionColor='#aaaaaa'
            underlineColor='#999999'
            activeUnderlineColor='#9f9f9f'
            label="Numéro de téléphone"
            value={username}
            onChangeText={text => setUsername(text)}
            onFocus={() => setInputMargin(125)}
            returnKeyType='next'
            onSubmitEditing={() => {
                setInputMargin(150)
            }}
            theme={{ colors: { text: '#bbbbbb', placeholder:'#bbbbbb' } }}
        />
        <TextInput
            style={{backgroundColor: '#2b2b2b', marginBottom: 20, marginHorizontal: 20}}
            selectionColor='#aaaaaa'
            underlineColor='#999999'
            activeUnderlineColor='#9f9f9f'
            value={password}
            label="Mot de passe"
            onChangeText={text => setPassword(text)}
            secureTextEntry
            returnKeyType='go'
            onFocus={() => setInputMargin(100)}
            onSubmitEditing={() => {
                setInputMargin(150);
                setActivityIndicatorState(true);
                signIn({ username: username, password: password, saveToken: isSwitchOn });
            }}
            theme={{ colors: { text: '#9f9f9f', placeholder:'#bbbbbb' } }}
        />
        <View style={{
            width: 200, 
            display: 'flex', flexDirection:'row', 
            alignSelf: 'center',
            justifyContent: 'space-around',
            alignItems: 'center',
            marginBottom: 20,
          }}>
          <Text style={{color: '#aaaaaa'}}>Se souvenir de moi</Text>
          <Switch style={{ display: 'flex', justifyContent: 'center'}} 
            color='rgba(66, 182, 64, 0.75)'
            value={isSwitchOn} onValueChange={onToggleSwitch} />
        </View>
        <Button 
          // onPress={() => console.log('asd')} 
          onPress={() => {
              console.log("--- username: " + username + " , password: " + password, serverAddress + "connexion");
              setActivityIndicatorState(true);
              fetch(serverAddress + "connexion", {
                method: 'POST',
                headers: {
                'Content-Type': 'application/json',
                "ngrok-skip-browser-warning":"any"
                },
                body: JSON.stringify({"username": username, "password": password}) 
              })
              .then(response => response.json())
              .then(result => {
                  console.log(result, result);
                  setActivityIndicatorState(false);
                  signIn({ username: result.name, password: result.password, saveToken: isSwitchOn });
              })
              .catch(error => {
                  console.log('loginHandle error', error);
                  Alert.alert('Erreur', 'Nom d\'utilisateur ou mot de passe incorrect.', [
                      {text: 'Okay'}
                  ]);
                  setActivityIndicatorState(false);

              });

              // Testing: connect without using the server
              // signIn({ username: 'dev', password:'dev', saveToken: true });
          }} 
          mode="contained"
          contentStyle={{height: '100%'}}
          labelStyle={{color:'#dddddd'}}
          style={styles.button}>
          {activityIndicatorState ? <ActivityIndicator animating={true} color={"#bbbbbb"} /> : "Sign in"}
        </Button >
        </KeyboardAvoidingView>
        </View>

        </TouchableWithoutFeedback>
      </View>
    );
};
  
  //------------------Styles-----------------------
const styles = StyleSheet.create({
    container : {
    //   top: margintopapps(ScreenWidth),
      flex:1,
      backgroundColor:'#2b2b2b',
    },
  
    containerauth : {
      flex:0,
    //   top:marginTopContainer (ScreenWidth),
      alignItems: 'center',
      justifyContent:'center',
      backgroundColor:'#2b2b2b',
    },

    containerattribut : {
    //   marginTop: marginEntreEl(ScreenWidth),// margsinTop:20,  
      backgroundColor:'#2b2b2b',     
    },

    inputBox: {
    //   width:widthBtn (ScreenWidth),
    //   height:heightBtn (ScreenWidth),
      backgroundColor:'#252525',
      paddingHorizontal:16,
    //   fontSize:fontSizer(ScreenWidth) ,
      color:'#ffffff',
      marginVertical: 8,
      borderWidth: 1,      
    },
  
    rememberView:{
    //   width:widthBtn (ScreenWidth),
      flexDirection:'row',
      alignItems:'flex-start',
    },

    rememberText:{
    //   marginLeft:marginleftElem (ScreenWidth),
      alignItems: 'flex-start',
      
      marginEnd:ScreenWidth-(ScreenWidth*90/100),     
    //   fontSize:fontSizer(ScreenWidth),
      color:'#ffffff',
    },

    rememberBtn:{
      width:ScreenWidth-(ScreenWidth*30/100),
      height:ScreenHeight-(ScreenHeight*50/100),

    },

    textLabel:{     
    //   fontSize:fontSizer(ScreenWidth), // fontSize:16,
    //   marginLeft:marginleftElem (ScreenWidth),  
      color:'#ffffff',
    },

    button: {
    //   width:widthBtn (ScreenWidth),
      height: 60,
      backgroundColor:'rgb(60, 170, 74)',
      borderRadius: 5,
      marginHorizontal: 20,
      marginVertical: 10,
      justifyContent:'center',
    //   borderColor:'#000000',
    //   borderWidth: 1,      
    },

    buttonText: {
        fontSize: 20,
    //   fontSize:fontSizer(ScreenWidth),
        color:'#ffffff',
        textAlign:'center'
    },

    errorText :{
    //   fontSize:fontSizer(ScreenWidth),
      alignSelf: 'center',
      color:'#C62828'
    },

    logo :{      
    //   top: marginView(ScreenWidth),
      width:ScreenWidth,     
      resizeMode: "contain",
    //   borderWidth:1, borderColor:'white',
    },
                                 
     
});