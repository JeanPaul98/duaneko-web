import React, { useState, useEffect, useContext, useRef } from 'react';
import { Text, FlatList, View, TouchableOpacity, TouchableWithoutFeedback, Image, Dimensions, ScrollView, Keyboard } from 'react-native';
import { StatusBar } from 'expo-status-bar';
import * as ImageManipulator from 'expo-image-manipulator';
import * as Location from 'expo-location';
import { serverAddress } from '../composants/SocketContext';
import { AuthContext } from '../composants/AuthContext';
import CameraScreen from './CameraScreen';
import SplashScreen from './SplashScreen';
import TopBarNav from '../composants/TopBarNav';
import {Picker} from '@react-native-picker/picker';
import { Title, Card, TextInput } from 'react-native-paper';
import { getStatusBarHeight } from 'react-native-status-bar-height';
import Icon from 'react-native-vector-icons/MaterialIcons';
import { useNavigation } from '@react-navigation/native';
// import Slider from '@react-native-community/slider';

export default function AjouterSignalement(props) {
    const { loginState } = useContext(AuthContext);
    const socket1 = loginState.socket;
    const navigation = useNavigation();
    const [open, setOpen] = useState(false);
    const handleOpen = () => setOpen(!open);
    const handleClose = () => setOpen(false);
    const [niveauUrgence, setNiveauUrgence] = useState(1);
    const [progressUpload, setprogressUpload] = useState("");
    const [loading, setLoading] = useState(false);
    const [pics, setPics] = useState([]);
    const [depotoirsSauvages, setDepotoirsSauvages] = useState(true);

    // const imageReceived = route.params?.pic;
    useEffect(() => {
        // console.log("current route:\n", route, '\n\nparent route:\n', navigation.getParent());
        // console.log("- AjouterSignalement: params received: " + JSON.stringify(props.route.params));
        if(props.route.params.pics) { 
            setPics(props.route.params.pics);
        }
        
    }, [props.route.params]);

    const renderImage = ({ item, i }) =>  
      <TouchableOpacity 
        key={i}
        activeOpacity={0.8}
        // onPress={() => navigation.navigate("SignalementPicsScreen", {pic: item.picsUri, sig: route.params.sig})}
        style={{ 
        // width: Dimensions.get('window').width, 
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        // borderColor: 'green', borderWidth: 1,
        padding: 0,
        // height: '70%',
      
      }}>
        <Image style={{
        // borderColor: 'red', borderWidth: 1,
            margin: 10,
          width: Dimensions.get('window').width/2, 
        //   width: Dimensions.get('window').width/2, 
        //   height: Dimensions.get('window').height/2 
          height: Dimensions.get('window').height/2 
        }}
          source={{ uri: item }}
        />
      </TouchableOpacity>
    ;

    const handleAjouter = async () => {
        setLoading(true);
        console.log("current route:");
        const timestamp = new Date().toISOString();
        if (pics.length === 0) {                     
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${loginState.location.coords.latitude}&lon=${loginState.location.coords.longitude}&zoom=18&addressdetails=1`)
            .then(response => response.json())
            .then((osmObj) => {
                setprogressUpload(JSON.stringify(osmObj.address)
                    .split(",").join("\n")
                    .split("{").join("")
                    .split("}").join(""));
                // add signalement details to db
                socket1.emit("ajouterSignalement", {
                    id: timestamp + loginState.user, 
                    util: loginState.user,
                    latitude: loginState.location.coords.latitude,
                    longitude: loginState.location.coords.longitude,
                    cite: osmObj.address.city || osmObj.address.village || osmObj.address.quarter || osmObj.address.state,
                    date: timestamp,
                    type: depotoirsSauvages ? "Dépotoirs sauvages" : "Caniveaux bouchés",
                    urgence: niveauUrgence,
                    commentaires: "tres sale",
                    categorie: "signale",
                    numPics: pics.length
                });
                setLoading(false);
                handleClose();
                setPics([]);
                navigation.navigate('Signalements');
            })
            .catch(() => {
                console.log('fetch error')
                setLoading(false);navigation.navigate('Signalements');
            });
        }
        else 
            // obtenir details address 
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${loginState.location.coords.latitude}&lon=${loginState.location.coords.longitude}&zoom=18&addressdetails=1`)
            .then(response => response.json())
            .then((osmObj) => {
                console.log('fetched address')

                pics.map((pic, i) => {
                    // convertir image
                    ImageManipulator.manipulateAsync(pic, [], { base64: true, compress: 0.4 })
                    .then(response => {
                        // uploadPic to server
                        console.log('- Telechargement photo ' + i +  ' vers serveur...');
                        fetch(serverAddress + "uploadPic", {
                            method: 'POST',
                            headers: {
                                "Accept": "application/json",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                id: timestamp + loginState.user + '_' + i,
                                img: response
                            })
                        })
                        .then(response => response.text())
                        .then((result) => {
                        })
                        .catch((err) => {
                            console.log('server error')
                            setLoading(false);navigation.navigate('Signalements');
                        });
                    })
                    .catch(() => {
                        console.log('image manipulator error');
                        setLoading(false);navigation.navigate('Signalements');
                    })
                });
                console.log('uploaded pics')

                setprogressUpload(JSON.stringify(osmObj.address)
                    .split(",").join("\n")
                    .split("{").join("")
                    .split("}").join(""));
                // add signalement details to db
                socket1.emit("ajouterSignalement", {
                    id: timestamp + loginState.user, 
                    util: loginState.user,
                    latitude: loginState.location.coords.latitude+'',
                    longitude: loginState.location.coords.longitude+'',
                    cite: osmObj.address.city || osmObj.address.village || osmObj.address.quarter || osmObj.address.state,
                    date: timestamp,
                    type: depotoirsSauvages ? "Dépotoirs sauvages" : "Caniveaux bouchés",
                    urgence: '' + niveauUrgence,
                    commentaires: "tres sale",
                    categorie: "signale",
                    numPics: pics.length
                });
                setLoading(false);
                handleClose();
                setPics([]);
                navigation.navigate('Signalements');
            })
            .catch(() => {
                console.log('fetch error')
                setLoading(false);navigation.navigate('Signalements');
            });


    };

    const obtenirAdresse = async () => {
        // console.log('imageReceived: ', imageReceived);
        setprogressUpload('Attente position...');
        // 1. get location
        Location.getCurrentPositionAsync({accuracy: loginState.locationAccuracy})
        .then((location1) => {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${location1.coords.latitude}&lon=${location1.coords.longitude}&zoom=18&addressdetails=1`)
            .then(response => response.json())
            .then((osmObj) => {
                // console.log('osmObj', osmObj);
                setprogressUpload(JSON.stringify(osmObj.address).replaceAll(',', '\n').replaceAll('{', '').replaceAll('}', ''));
            });
        });
    };
    
    if(loading) return <SplashScreen opacity={0.3}/>
    return (
        <TouchableWithoutFeedback style={{ flex: 1 }}  onPress={() => {Keyboard.dismiss();}}>
            <>
            <StatusBar style="dark" />
            {/* TOP BAR */}
            <View style={{ 
                marginTop: getStatusBarHeight(),
                height: 60, width: "100%",
                borderWidth:1, borderColor:'white', 
                backgroundColor: '#ffffff99',
                // borderBottomRightRadius: 15,
                // borderBottomLeftRadius: 15,
                // borderTopRightRadius: statusbarHeight-5,
                borderRadius: 15,
                position: 'absolute',
                left: 0,
                zIndex: 5,
                top: 0,
                display: 'flex',
                flexDirection:'row',
                alignItems: 'center',
                justifyContent: 'space-between',
                paddingHorizontal: 15,
                }}>
                <TouchableOpacity title="Menu" mode='contained' 
                    onPress={() => {
                        setPics([]);
                        props.navigation.navigate(props.route.params.post);
                    }}
                    style={{ 
                        // width: 40,
                        height: 40, 
                        // backgroundColor: 'rgba(0,0,0, 0)',
                        // borderColor:'rgba(100,100,100, 0)',  
                        display: 'flex',
                        justifyContent: 'center',
                        alignItems: 'center',
                        // marginLeft: 15, 
                        // borderWidth:1, borderColor:'red', 
                    }} >
                    <Text style={{ fontSize: 17, color: 'crimson' }}>Annuler</Text>
                </TouchableOpacity>
                <TouchableOpacity title="carte" mode='contained'
                    onPress={() => handleOpen()}
                    style={{ 
                        // width: 40,
                        height: 40, 
                        backgroundColor: '#3CAA4A',
                        // borderColor:'rgba(100,100,100, 0)',  
                        display: 'flex',
                        justifyContent: 'center',
                        alignItems: 'center',
                        // marginRight: 15,
                        // borderWidth:1, borderColor:'red', 
                        borderRadius: 5,
                        paddingHorizontal: 10,
                    }} >
                    <Text style={{ fontSize: 17, color: '#f6f6f6' }}>Ajouter</Text>
                </TouchableOpacity>     

            </View>
            {
                open ? <View style={{ 
                // borderWidth:1, borderColor:'red', 
                position: 'absolute',
                width: '100%',
                height: '100%',
                zIndex: 5,
                top: 0,
                // top: '30%',
                left: 0,
                backgroundColor: '#222222ef',
                // borderRadius: 5,
                display: 'flex',
                justifyContent: 'center',
                alignItems: 'flex-start',
                paddingHorizontal: 25,
                // paddingVertical: 25,
                }}>
                <View 
                    style={{ 
                        width: '100%',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'flex-start',
                    }}
                >
                    <Text id="modal-modal-description" style={{ width: '100%', color: 'white', marginBottom: 20 }}>
                        Êtes-vous certain de vouloir publier ce signalement?
                    </Text>
                </View>
                <View style={{ 
                    width: '100%',
                    display: 'flex',
                    flexDirection:'row-reverse',
                    alignItems: 'center',
                    justifyContent: 'space-between',
                    }}>
                    <TouchableOpacity
                    mode='contained'
                    style={{ 
                        height: 40, width: 100,
                        display: 'flex',
                        flexDirection:'row',
                        alignItems: 'center',
                        justifyContent: 'center',
                        borderRadius: 5,
                        backgroundColor: '#3CAA4A'
                    }}
                    onPress={() => handleAjouter()}>
                    <Icon name='add' size={25} color={'#f4f4f4'}/>
                    <Text style={{color: '#f4f4f4'}}>Ajouter</Text>
                </TouchableOpacity>     
                <TouchableOpacity
                    mode='contained'
                    style={{ 
                        height: 40, width: 100,
                        display: 'flex',
                        flexDirection:'row',
                        alignItems: 'center',
                        justifyContent: 'center',
                        borderRadius: 5,
                        backgroundColor: '#f4f4f4'
                    }}
                    onPress={() => handleClose()}>
                    <Icon name='close' size={25} color={'#f45555'}/>
                    <Text style={{color: '#f45555'}}>Annuler</Text>
                </TouchableOpacity> 
                </View>
                </View> : null 
            }
            {/* TITLE */}
            {/* <View style={{ 
                marginTop: 80,
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
                <Title style={{fontSize: 30, color:'#222222', fontWeight:'bold'}}>Nouveau signalement</Title>
            </View> */}
            <ScrollView>
                {/* PICS */}
                <Text style={{ fontSize: 18, marginBottom: 25, marginTop: 100, marginHorizontal: 20, fontWeight:'bold' }}>
                    Photos
                </Text>
                <View style={{
                    display: 'flex',
                    height: (pics.length > 0) ? Dimensions.get('window').height/1.3 : 100,
                    borderWidth: 5, borderColor:'#cccccc55', 
                        // borderStyle: 'dotted',
                    borderRadius: 1,
                    borderStyle: 'dashed',
                    justifyContent: 'center',
                    alignItems: 'center',
                    marginBottom: 10,
                    marginLeft: 20,
                    
                    marginRight: 20,
                    padding: 20
                }} >

                    {(pics.length > 0) ?
                        // pics.map( (pic, i) => {
                        //     return (<Image source={{uri: pic}} key={i}
                        //     style={{
                        //         width: 100,
                        //         height: 150
                        //     }} /> )
                        // })
                        <FlatList 
                            style={{ 
                                // borderWidth:1, borderColor:'red', 
                                marginBottom: 20,
                                // height: '80%',
                             }}
                            data={pics}
                            keyExtractor={({ id }, index) => id}
                            renderItem={renderImage}
                            removeClippedSubviews={true}
                            horizontal
                            // onRefresh={onRefresh}
                            // refreshing={isFetching}
                            // initialNumToRender={5}
                            // onScroll={(e) => setPos(e.nativeEvent.contentOffset.y)}
                            // ListFooterComponent={<View style={{  height: 50,}}></View>}
                        />
                        : null
                    }
                    <TouchableOpacity title="camera" mode='contained'
                        onPress={() => navigation.navigate("Camera", { post: props.route.params.post })}
                        style={{ 
                            // borderWidth:1, borderColor:'red', 
                            // borderRadius: 50,
                            height:50, width: 50,
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                        }} >
                        <Icon name='camera-alt' size={50}color={'black'}/>
                    </TouchableOpacity>
                </View>
                {/* ADDRESS */}
                {/* <Button onPress={() => obtenirAdresse()} title="Obtenir mon adresse" />
                <Text style={{marginTop: 10, marginLeft: 15}}>{progressUpload}</Text> */}
                
                {/* Type */}
                <Text style={{ fontSize: 18, marginTop: 20, marginBottom: 20, marginHorizontal: 20, fontWeight:'bold' }}>
                Type
                </Text>
               
                <View style={{ 
                    display:'flex', 
                    flexDirection:'row', 
                    // borderWidth:1,
                    alignItems: 'center',
                    justifyContent: 'space-around',

                }}>
                    <TouchableOpacity 
                        onPress={() => setDepotoirsSauvages(true)}
                        activeOpacity={1}
                        style={{ 
                            width: 100, 
                            alignItems: 'center',
                            // borderWidth:1,
                        }}>
                        <Image style={{
                        width: 100, 
                        height: 100,
                        borderColor: depotoirsSauvages ? "rgba(66, 182, 64, 1)" : '#ccccccaa',
                        borderWidth: 4,
                        borderRadius: 50
                        }}
                        source={require('../assets/typeDepotoirsSauvages.png')} />
                    </TouchableOpacity>                    
                    <TouchableOpacity
                        activeOpacity={1}
                        onPress={() => setDepotoirsSauvages(false)}
                        style={{ 
                            width: 100, 
                            alignItems: 'center',
                            // borderWidth:1,
                        }}>
                        <Image style={{
                        width: 100, 
                        height: 100,
                        borderColor: !depotoirsSauvages ? "rgba(66, 182, 64, 1)" : '#ccccccaa',
                        borderWidth: 4,
                        borderRadius: 50
                        }}
                        source={require('../assets/signalement-type-caniveaux-bouchees.png')} />
                    </TouchableOpacity>
                </View>

                <View style={{ 
                    marginTop: 10,
                    display:'flex', 
                    flexDirection:'row', 
                    // borderWidth:1,
                    alignItems: 'center',
                    justifyContent: 'space-around',
                    marginBottom: 10,

                }}>
                    <Text style={{ fontSize: 12, color: depotoirsSauvages ? "rgba(66, 182, 64, 1)" : "#555555" }}>Dépotoirs sauvages</Text>
                    <Text style={{ fontSize: 12, color: !depotoirsSauvages ? "rgba(66, 182, 64, 1)" : "#555555" }}>Caniveaux bouchées</Text>
                </View>
                {/* Priorite */}
                <Text style={{ fontSize: 18, margin: 20, fontWeight:'bold' }}>
                    Niveau d'urgence
                </Text>
                <View style={{ 
                    // borderWidth:1, borderColor:'red', 
                    // borderRadius: 50,
                    // height:50, width: 50,
                    display: 'flex',
                    flexDirection: 'row',
                    alignItems: 'center',
                    justifyContent: 'space-around',
                    paddingHorizontal: '10%',
                    marginBottom: 20,
                }}>
                    <TouchableOpacity title="camera" mode='contained' activeOpacity={0.8}
                        onPress={() => setNiveauUrgence(1)}
                        style={{ 
                            borderWidth: 4, borderColor: niveauUrgence === 1 ? 'orangered' : '#ccccccaa', 
                            borderRadius: 10,
                            backgroundColor: 'tomato',
                            height:60, width: 60,
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                        }} >
                        <Text style={{ fontSize: 18, color: 'white', fontWeight:'bold' }}>
                            !
                        </Text>
                    </TouchableOpacity>
                    <TouchableOpacity title="camera" mode='contained' activeOpacity={0.8}
                        onPress={() => setNiveauUrgence(2)}
                        style={{ 
                            borderWidth: 4, borderColor: niveauUrgence === 2 ? 'red' : '#ccccccaa', 
                            backgroundColor: 'orangered',
                            borderRadius: 10,
                            height:60, width: 60,
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                        }} >
                        <Text style={{ fontSize: 18, color: 'white', fontWeight:'bold' }}>
                            !!
                        </Text>
                    </TouchableOpacity>
                    <TouchableOpacity title="camera" mode='contained' activeOpacity={0.8}
                        onPress={() => setNiveauUrgence(3)}
                        style={{ 
                            borderWidth: 4, borderColor: niveauUrgence === 3 ? '#99000099' : '#ccccccaa', 
                            backgroundColor: 'red',
                            borderRadius: 10,
                            height:60, width: 60,
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                        }} >
                        <Text style={{ fontSize: 18, color: 'white', fontWeight:'bold' }}>
                            !!!
                        </Text>
                    </TouchableOpacity>
                </View>
                {/* Commentaires */}
                <Text style={{ fontSize: 18, marginBottom: 15, marginHorizontal: 20, fontWeight:'bold' }}>
                    Informations supplémentaires
                </Text>
                <TextInput mode="outlined" 
                    multiline={true}
                    numberOfLines={10}
                    style={{backgroundColor: '#f4f4f4', marginBottom: 20, marginHorizontal: 20, height:200, textAlignVertical: 'top',}}
                    selectionColor='#aaaaaa'
                    underlineColor='#999999'
                    activeUnderlineColor='#9f9f9f'
                    // value={password}
                    // label="Entrez un commentaire ou une description si vous le souhaitez."
                    // onChangeText={text => setPassword(text)}
                    // returnKeyType='go'
                    // onFocus={() => setInputMargin(100)}
                    // onSubmitEditing={() => {
                    //     setInputMargin(150);
                    //     setActivityIndicatorState(true);
                    //     signIn({ username: username, password: password, saveToken: isSwitchOn });
                    // }}
                    theme={{ colors: { text: '#9f9f9f', placeholder:'#bbbbbb' } }}
                />
            </ScrollView>

            </>
        </TouchableWithoutFeedback>
    );
  }