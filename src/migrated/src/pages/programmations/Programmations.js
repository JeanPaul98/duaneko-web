import * as React from 'react';
import { View, Text, TouchableOpacity, styles, FlatList } from 'react-native';
import { Button, Title } from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialIcons';
import { StatusBar } from 'expo-status-bar';
import { getStatusBarHeight } from 'react-native-status-bar-height';
import dayjs from 'dayjs';
import localefr from 'dayjs/locale/fr';
import { AuthContext } from '../../composants/AuthContext';
import TopBarNav from '../../composants/TopBarNav';
import { socket } from '../../composants/SocketContext';

export default function Programmations({ navigation }) {
    dayjs.locale('fr');
    const { signOut, loginState } = React.useContext(AuthContext);
    const statusbarHeight = getStatusBarHeight();
    const [isFetching, setIsFetching] = React.useState(false);

    const renderSignalement = ({ item, i }) =>  
        <TouchableOpacity onPress={() => navigation.navigate('Programmation', {sig: item})}>
            <View style={{padding: 20, width: '100%', borderBottomColor: '#cccccc', borderBottomWidth: 1}}>
    
                <Text style={{ fontSize: 14, color:'#333333', marginBottom: 10}}>
                    Prévu : {' '}
                    {dayjs(item.dateNettoyage).format('dddd, D MMMM YYYY')} @ {' '}
                    {dayjs(item.dateNettoyage).format('HH:mm')}
                </Text>  
                <Text style={{ fontSize: 12, color:'grey'}}>Utilisateur : {item.util}</Text>
                <Text style={{ fontSize: 12, color:'grey'}}>
                    Posté : {' '}
                    {dayjs(item.dateCreation).format('dddd, D MMMM YYYY')} @ {' '}
                    {dayjs(item.dateCreation).format('HH:mm')}
                </Text>          
            </View>
        </TouchableOpacity>

    ;
    const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
    const onRefresh = async () => {
        setIsFetching(true);
        socket.emit('getProgrammations', '');
        await sleep(1000);
        setIsFetching(false);
    };

    React.useEffect(() => {
        socket.emit('getProgrammations', '');
    }, []);

    return (
        <View style={{ flex: 1,  width: '100%'}}>
            <StatusBar style="dark" />
            {/* TOP BAR */}
            <TopBarNav title={'Programmations'} />
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
                <Title style={{fontSize: 30, color:'#222222', fontWeight:'bold'}}>Programmations</Title>
            </View> */}
            <View style={{  height: 80,}}></View>

            <View style={{ 
                    flex: 1,display: 'flex',  width: '100%',
                    // borderColor:'rgba(100,100,100, 1)', borderWidth:1, 
                    alignItems: 'center',
                    justifyContent: 'center',
                }}>
                {
                    (loginState.programmations && loginState.programmations.length !== 0) ? (
                        <FlatList style={{ height: 350,  width: '100%' }}
                            onRefresh={onRefresh}
                            refreshing={isFetching}
                            data={loginState.programmations}
                            keyExtractor={({ id }, index) => id}
                            renderItem={renderSignalement}
                            removeClippedSubviews={true}
                            // initialNumToRender={5}
                            // onScroll={(e) => setPos(e.nativeEvent.contentOffset.y)}
                            ListFooterComponent={<View style={{  height: 50,}}></View>}
                        />
                    ) : <Text style={{fontSize: 16, marginBottom: 150}}>Pas de programmations</Text>
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
                    onPress={() => navigation.navigate('Ajouter une programmation')} >
                    <Icon name='add-circle' size={30} color={'#3CAA4A'} />
                    <Text style={{ 
                        color: '#3CAA4A',
                        fontSize: 16.5,
                        fontWeight: '700', 
                        marginLeft: 8,
                    }}>Programmation</Text>
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