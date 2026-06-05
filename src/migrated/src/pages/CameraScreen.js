import React, { useState, useEffect, useRef } from 'react';
import { Text, Dimensions, View, TouchableOpacity, Image, ScrollView } from 'react-native';
import { Camera } from 'expo-camera';
import { StatusBar } from 'expo-status-bar';
import { useNavigation } from '@react-navigation/native';
import Icon from 'react-native-vector-icons/MaterialIcons';
import * as ImageManipulator from 'expo-image-manipulator';
import { socket, serverAddress } from '../composants/SocketContext';
import { getStatusBarHeight } from 'react-native-status-bar-height';

export default function CameraScreen(props) {
    const navigation = useNavigation();
    const cameraRef = useRef();
    const [hasPermission, setHasPermission] = useState(null);
    const [type, setType] = useState(Camera.Constants.Type.back);
    const [isCameraReady, setIsCameraReady] = useState(false);
    const [isPreview, setIsPreview] = useState(false);
    // const [image1, setImage1] = useState(null);
    const [pics, setPics] = useState([]);
  
    useEffect(() => {
      (async () => {
        const { status } = await Camera.requestCameraPermissionsAsync();
        setHasPermission(status === 'granted');
      })();
    }, []);
  
    const onCameraReady = () => {
      setIsCameraReady(true);
    };

    const onSnap = async () => {
      if (cameraRef.current) {
        const options = { quality: 0.7, base64: true };
        const picSnapped = await cameraRef.current.takePictureAsync(options);
        // cameraRef.current.pausePreview();
        setIsPreview(true);
        // console.log(picSnapped);
        if (picSnapped) {
          // setImage1(picSnapped.uri);
          let x = [...pics];
          x.push(picSnapped.uri);
          setPics(x);
        }
      }
    };
  
    const cancelPreview = async () => {
      // cameraRef.current.resumePreview().then(() => setIsPreview(false));
      let x = [...pics];
      x.pop();
      setPics(x);
      setIsPreview(false);
    };
  
    // var imageLink1 = 'data:image/png;base64,' + image1;
    // console.log(imageLink1);
    if (hasPermission === null) {
      return <View />;
    }
    if (hasPermission === false) {
      return <Text>No access to camera</Text>;
    }
    
    return (
      <View style={{
        backgroundColor: "#444444",
        width: Dimensions.get('window').width,
        height: Dimensions.get('window').height+15,
        // position: 'absolute',
        // left: -240,
        // left: 0,

        // zIndex: 6,
        // top: 0,
        // top: -380,
        overflow: 'hidden',
        }}>
        <StatusBar style="light" />
        { isPreview ? (
          <View>
          {(pics.length > 0) ?
              <Image source={{uri: pics[pics.length-1]}} 
              style={{
                  // position: 'absolute',
                  // top: 0,
                  // left: 0,
                  width: Dimensions.get('window').width,
                  height: Dimensions.get('window').height+15}}
            /> : 
            <Text style={{ fontSize: 20, marginBottom: 10, color: 'white' }}>Traitement</Text>
          }
          <TouchableOpacity
            onPress={cancelPreview}
            activeOpacity={0.7}
            style={{ 
              position: 'absolute',
              top: 0,
              left: 10,
              zIndex: 5,
              backgroundColor: '#00000055',
              marginTop: 10 + getStatusBarHeight(),
              width: 80, height: 40,
              justifyContent: 'center',
              alignItems: 'center',
              borderRadius: 5
            }}>
            <Text style={{ fontSize: 20, color: 'white' }}> Retour </Text>
          </TouchableOpacity>
          <TouchableOpacity
            // onPress={() => navigation.navigate('Ajouter un signalement' , {pic: image1})}
            onPress={() => {
                // console.log('pics : ',  pics);
                // ImageManipulator.manipulateAsync(image1, [], { base64: true })
                // .then(response => {
                //     socket.emit('uploadPic', response);
                // })
                // .catch((err) => console.log('image manipulator error', err))
                navigation.navigate("Ajouter un signalement", {pics: pics, post: props.route.params.post});
            }}  
            activeOpacity={0.7}
            style={{ 
              zIndex: 5,
              position: 'absolute',
              top: 0,
              right: 10,
              backgroundColor: '#00000055',
              marginTop: 10 + getStatusBarHeight(),
              width: 180, height: 40,
              justifyContent: 'center',
              alignItems: 'center',
              borderRadius: 5
            }}
          >
            <Text style={{ 
              // zIndex: 5, 
              fontSize: 20, 
              color: 'white' }}> Utiliser images({pics.length}) </Text>
          </TouchableOpacity>
          <TouchableOpacity
            onPress={() => setIsPreview(false)}  
            activeOpacity={0.7}
            style={{ 
              zIndex: 5,
              position: 'absolute',
              bottom: 40,
              right: 40,
              backgroundColor: '#00000055',
              marginTop: 10 + getStatusBarHeight(),
              width: 240, height: 40,
              justifyContent: 'center',
              alignItems: 'center',
              borderRadius: 5
            }} >
            <Text style={{ fontSize: 20, color: 'white' }}> Prendre une autre photo </Text>
          </TouchableOpacity>
          </View>
          ) : (
          <Camera style={{ 
            flex: 1,

          }} type={type} onCameraReady={onCameraReady} ref={cameraRef}>
            <View
              style={{
                flex: 1,
                backgroundColor: 'transparent',
                justifyContent: 'space-between',
    
                // flexDirection: 'row',
              }}>
              {/* BOUTON RETOUR */}
              <TouchableOpacity
                style={{
                  alignItems: 'flex-start',
                  marginLeft: 20,
                  marginTop: 35,
                  width: 70
                }}
                onPress={() => navigation.goBack()}>
                <Text style={{ fontSize: 20, marginBottom: 10, color: 'white' }}> Retour </Text>
                            {/* <Image style={{ marginBottom: 10 }}
                    source={require('../assets1/images/closebutton.png')}/>*/}
              </TouchableOpacity>
              {/* BOUTONS CAMERA */}
              <View style={{marginLeft: '20%', width: '100%', flexDirection:'row-reverse', marginBottom: 40, justifyContent:'space-around'}}>
                  <TouchableOpacity
                    style={{
                      // flex: 1,
                      // alignItems: 'flex-end',
                      // justifyContent: 'flex-end',
                      // marginLeft: '20%',
                      // marginBottom: 40,
                    }}
                    onPress={() => {
                      setType(
                        type === Camera.Constants.Type.back
                        ? Camera.Constants.Type.front
                        : Camera.Constants.Type.back
                        );
                      }}>
                    <Icon style={{ color: '#ffffff'}} name='flip-camera-ios' size={40}/>
                  </TouchableOpacity>
                  <TouchableOpacity
                    activeOpacity={0.7}
                    disabled={!isCameraReady}
                    onPress={onSnap}
                    style={{width: 65, height: 65, backgroundColor: 'white', 
                    borderRadius: 35, 
                  }}
                  />
                  <TouchableOpacity
                      activeOpacity={0}
                      disabled={!isCameraReady}
                      // onPress={onSnap}
                      style={{width: 50, height: 50, backgroundColor: 'rgba(1, 1, 1, 0)', 
                      borderRadius: 35, 
                    }}
                  />
              </View>
            </View>
          </Camera>
        )}
      </View>
    );
  }