import * as React from 'react';
import { View, Text, TouchableOpacity, FlatList } from 'react-native';
import { Title } from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialIcons';
import Icon1 from 'react-native-vector-icons/MaterialCommunityIcons';

import { StatusBar } from 'expo-status-bar';
import dayjs from 'dayjs';
import localefr from 'dayjs/locale/fr';
import { AuthContext } from '../../composants/AuthContext';
import TopBarNav from '../../composants/TopBarNav';
import { socket } from '../../composants/SocketContext';

export default function Actus({ navigation }) {
    const { loginState } = React.useContext(AuthContext);
    React.useEffect(() => {
        socket.emit('getActus', '');
        console.log('axtus')
    }, []);
    dayjs.locale('fr');

    const renderSignalement = ({ item, i }) =>  
        <TouchableOpacity onPress={() => navigation.navigate('Actu', {sig: item})}>
            <View style={{padding: 20, width: '100%', borderBottomColor: '#cccccc', borderBottomWidth: 1}}>
    
                <Text style={{ fontSize: 14, color:'#333333', marginBottom: 5}}>
                    {item.titre}
                </Text>  
                <Text style={{ fontSize: 12, color:'grey', marginBottom: 5}}>{item.source}</Text>
                <Text style={{ fontSize: 12, color:'grey'}}>{item.util}</Text>
                <Text style={{ fontSize: 12, color:'grey'}}>
                    {dayjs(item.date).format('dddd, D MMMM YYYY')} @ {' '}
                    {dayjs(item.date).format('HH:mm')}
                </Text>          
            </View>
        </TouchableOpacity>
    ;

    const [isFetching, setIsFetching] = React.useState(false);
    const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
    const onRefresh = async () => {
        setIsFetching(true);
        socket.emit('getActus', '');
        socket.emit('getCount', '');
        await sleep(1000);
        setIsFetching(false);
    };

    return (
        <View style={{ flex: 1,}}>
            <StatusBar style="dark" />
            {/* TOP BAR */}
            <TopBarNav title={'Actualités'}/>
            {/* <View style={{ 
                height: 150,
                // width: "100%",
                display: 'flex',
                alignItems: 'flex-end',
                justifyContent: 'flex-start',
                flexDirection:'row',
                // borderColor:'rgba(100,100,100, 1)', borderWidth:2, 
                padding: 20,
                backgroundColor: '#f4f4f400',
            }}>
                <Title style={{fontSize: 30, color:'#222222', fontWeight:'bold'}}>Actus</Title>
            </View> */}
            <View style={{  height: 80,}}></View>
            <View style={{ padding: 20, display: 'flex', flexDirection: 'row', justifyContent: 'center', alignItems: 'center'}}>
                <Icon1 name="map-marker" color={'red'} size={20} />
                <Text>{loginState.count?.count_sig || '-'} signalements</Text>
                <Icon1 name="map-marker" color={'gold'} size={20} />
                <Text>{loginState.count?.count_prog || '-'} programmations</Text>
            </View>
            
            <View style={{ 
                    flex: 1,display: 'flex',
                    // borderColor:'rgba(100,100,100, 1)', borderWidth:1, 
                    alignItems: 'center',
                    justifyContent: 'center',
                }}
            >
                {
                    ( loginState.actus && loginState.actus.length !== 0) ? (
                        <FlatList style={{ height: 350,  width: '100%' }}
                            data={loginState.actus}                            
                            onRefresh={onRefresh}
                            refreshing={isFetching}
                            keyExtractor={({ id }, index) => id}
                            renderItem={renderSignalement}
                            removeClippedSubviews={true}
                            // initialNumToRender={5}
                            // onScroll={(e) => setPos(e.nativeEvent.contentOffset.y)}
                            ListFooterComponent={<View style={{  height: 50,}}></View>}
                        />
                    ) : <Text style={{fontSize: 16, marginBottom: 150}}>Pas  d'actualités</Text>
                }
            </View>

             {/* BOTTOM BAR */}
             <View style={{ 
                position: 'absolute',
                right: 0,
                zIndex: 5,
                bottom: 0,
                height: 50,
                width: "100%",
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                flexDirection:'row',
                borderColor:'rgba(150,160,150, 0.2)', 
                borderTopWidth:1, 
                backgroundColor: 'rgba(255,255,255, 0.97)',
            }}>
                <TouchableOpacity title="Ajouter un signalement" 
                    style={{ 
                        height: 60,
                        alignItems: 'center',
                        justifyContent: 'space-between',
                        flexDirection:'row',
                        marginLeft: 15,
                    }}
                    onPress={() => navigation.navigate('Ajouter une actu')} >
                    <Icon name='add-circle' size={30} color={'#3CAA4A'} />
                    <Text style={{ 
                        color: '#3CAA4A',
                        fontSize: 16.5,
                        fontWeight: '700', 
                        marginLeft: 8,
                    }}>Actualité</Text>
                </TouchableOpacity>

                <View style={{ 
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'space-between',
                    flexDirection:'row',
                    // paddingHorizontal: 15
                }}>
                    <TouchableOpacity title="filter-list" 
                        contentStyle={{height:'100%', width: '100%'}}
                        style={{ 
                            height: 60,
                            alignItems: 'center',
                            justifyContent: 'space-between',
                            flexDirection:'row',
                            marginRight: 25,

                        }}>
                        <Icon name='filter-alt' size={30} color={'#3CAA4A'} />
                    </TouchableOpacity>
                    <TouchableOpacity title="search" 
                        style={{ 
                            // height:'100%', width: '100%',
                            alignItems: 'center',
                            justifyContent: 'space-between',
                            flexDirection:'row',
                            marginRight: 15,
                        }}>
                        <Icon name='search' size={30} color={'#3CAA4A'} />
                    </TouchableOpacity>
                </View>
            </View>
        </View>
    );
}