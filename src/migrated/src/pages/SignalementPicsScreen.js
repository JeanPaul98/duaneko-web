import React, { useState, useEffect, useCallback, useRef } from 'react';
import { Text, StyleSheet, View, TouchableOpacity, Image, Dimensions  } from 'react-native';
import Icon from 'react-native-vector-icons/MaterialIcons';

export default function SignalementPicsScreen({ navigation, route  }) {
    return (
      <View style={{ flex:1, backgroundColor:"white"}}>
          <TouchableOpacity title="my location" mode='contained'
              onPress={() => navigation.navigate('Signalement', {sig: route.params.sig})}
              style={{ 
                  position: 'absolute',
                  top: 30, right: 15,
                  zIndex: 5,
                  // borderWidth:1, borderColor:'red', 
                  // borderRadius: '50%',
                  height: 50, width: 50,
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
              }} >
              <Icon name='close' size={50} color={'white'}/>
          </TouchableOpacity>
          <Image style={{
            width: Dimensions.get('window').width, 
            height: Dimensions.get('window').height }}
            source={{ uri: route.params.pic }} />
      </View>
    )
  };

  const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: 'white',
    },
});