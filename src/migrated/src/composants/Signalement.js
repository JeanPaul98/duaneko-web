import React from 'react';
import { StyleSheet, Text, View, Image, TouchableOpacity } from 'react-native';
import { useNavigation } from '@react-navigation/native';
import relativeTime from 'dayjs/plugin/relativeTime';
import dayjs from 'dayjs';
import localefr from 'dayjs/locale/fr';
import Icon from 'react-native-vector-icons/MaterialIcons';
import { socket, serverAddress } from './SocketContext';

// SIGNALEMENT PROPS
// {
//     "id":"2021-10-17T04:22:58.106Zandee777",
//     "latitude":45.4616589284805,
//     "longitude":-75.76868011127924,
//     "cite":"Gatineau",
//     "util":"testuser",
//     "date":"2021-10-17T04:22:58.106Z",
//     "type":"Depotoirs sauvages",
//     "categorie":"signale",
// }
export default function Signalement(props) {
  const navigation = useNavigation();
  dayjs.extend(relativeTime);
  dayjs.locale('fr');
  var picsUri = (props.utilisateur !== 'andee777bot') ? serverAddress + 'photo/' + props.date + props.utilisateur + '_0' : serverAddress + 'photo/image';
//   console.log(serverAddress + 'photo/', props.sig);
  return (
    <TouchableOpacity onPress={() => navigation.navigate('Signalement', {sig: props.sig})}>
    <View style={{
            height: 110,
            width: "100%",
            backgroundColor: '#f4f4f4',
            flexDirection: 'row',
            alignItems: 'center',
            justifyContent: 'space-between',
            borderColor: "#e4e4e4", borderBottomWidth: 1,
            paddingHorizontal: 10,
            // borderWidth: 1, borderColor:'#3CAA4A'
        }}>
            {/* PIC */}
            <View style={{
                height: 75,
                width: 75,
                borderRadius: 50,
                alignItems: 'center',
                justifyContent: 'center',
                // borderColor: "red", borderWidth: 1,
            }}>
                <Image style={{
                        width: '100%',
                        height: '100%',
                        borderRadius: 50,
                        backgroundColor: '#66666655'
                    }}
                    source={{ uri: props.sig.numPics !== 0 ? picsUri : 'https://images.unsplash.com/photo-1675621968831-10d5117f0ad3?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80' }} />
            </View>
            {/* DETAILS */}
            <View style={{
            // width: "100%",
            // flexDirection: 'row',
            height: '100%',
            flex: 1,
            justifyContent: 'space-between',
            alignItems: 'flex-start',
            marginLeft: 20,
            // borderWidth: 1, borderColor:'black',
            paddingVertical: 15
            }}>
                {/* LINE 1 */}
                <View style={{
                        width: "100%",
                        flexDirection: 'row',
                        justifyContent: 'space-between',
                        alignItems: 'center',
                        // flexWrap: 'wrap'
                        // borderWidth: 1, borderColor:'blue'
                    }}>
                    <Text style={{
                            color: "black",
                            fontSize: 20,
                            fontWeight: '600',
                        }}>{props.distance} km</Text>
                    <Text numberOfLines={1} 
                        ellipsizeMode='tail' 
                        style={{
                            color: "rgb(151, 151, 151)",
                            fontWeight: '600',
                            fontSize: 12,
                            // overflow: 'hidden'
                            // flexWrap: 'nowrap',
                            // borderWidth: 1, borderColor:'blue',
                            maxWidth: '60%',
                            flex: 1,
                            alignItems: 'flex-end',
                            textAlign: 'right',
                            paddingLeft: 20,
                            
                        }}>{props.type}</Text>
                </View>

                {/* LINE 3 */}
                <View style={{
                        width: "100%",
                        // borderWidth: 1, borderColor:'green',
                        // marginTop: 10,
                        flexDirection: 'row',
                        justifyContent: 'flex-start',
                        alignItems: 'flex-end'
                    }}>
                    {(
                        (props.categorie == "programme") ? 
                            <Icon name="place" color={'#FFD700'} size={20} />
                        :
                            ((props.categorie == "nettoye") ?
                                <Icon name="place" color={'rgb(66, 182, 64)'} size={20} />
                            : 
                                <Icon name="place" color={'red'} size={20} />
                            )
                    )}
                    <Text style={{ fontSize: 12, color: 'rgb(191, 191, 191)'}}>{props.emplacement}</Text>
                </View>

                {/* LINE 4 */}
                <View style={{
                        width: "100%",
                        flexDirection: 'row',
                        justifyContent: 'space-between',
                        alignItems: 'flex-end',
                        // borderWidth: 2, borderColor:'blue'
                    }}>
                    
                    <View style={{
                        // borderWidth: 1, borderColor:'green',
                        // marginTop: 10,
                        flexDirection: 'row',
                        justifyContent: 'flex-start',
                        alignItems: 'flex-end'
                        }}>
                        
                        <Icon name="person" color={'rgb(191, 191, 191)'} size={15} />
                        <Text style={{
                                color: "rgb(191, 191, 191)",
                                fontSize: 12,
                            }}>@{props.utilisateur}
                        </Text>
                    </View>
                    <Text style={styles.textDateUtil}>{dayjs(props.date).fromNow()}</Text>
                </View>
            </View>
    </View>
    </TouchableOpacity>

  );
}

const styles = StyleSheet.create({
    photo: {
        // width: "80%",
        height: 100,
        width: 100,
        borderColor: "#e4e4e4",
        borderBottomWidth: 1,
        borderStyle: "solid",
        // backgroundColor: '#f4f4f4',
        alignItems: 'center',
        justifyContent: 'center',
    },
    typeSignalement: {
        width: "100%",
        paddingBottom: 5
    },
    source: {
        marginTop: 10,
        height: 40,
        width: "100%",
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center'
    },
    textDistanceEmplacement2: {
        color: "black",
    },
    distanceEmplacement2: {
        height: '100%',
        justifyContent: 'center',
        alignItems: 'center'
    },
    textDateUtil: {
        color: "rgb(191, 191, 191)",
        fontSize: 12,
        flexDirection: 'row',
        justifyContent: 'space-between',
    },

    tinyLogo33: {
        paddingBottom: 1,
        marginLeft: 5
    },
});