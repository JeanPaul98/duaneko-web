import React, { useEffect, useState, useRef, useContext } from 'react'
import { Text, TouchableOpacity, View } from 'react-native';
import { Checkbox, Title } from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialIcons';

export default function Filtres() {
    const [open, setOpen] = useState(false);
    const handleOpen = () => setOpen(!open);
    const handleClose = () => setOpen(false);
    const [checked, setChecked] = useState(false);
    const handleCheck = () => (checked === "checked") ? setChecked('unchecked') : setChecked('checked');
    if(open) return <View style={{ 
        // borderWidth:1, borderColor:'red', 
        position: 'fixed',
        width: '100%',
        height: '100%',
        zIndex: 15,
        top: 0,
        // top: '30%',
        left: 0,
        backgroundColor: '#222222ef',
        // borderRadius: 5,
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'flex-start',
        paddingHorizontal: 25,
        paddingVertical: 30,
        }}>
        <View >
        <Title style={{fontSize: 30, color:'white', fontWeight:'bold'}}>Filtres</Title>
        <Text style={{color:'white'}}>Catégorie</Text>
        <Checkbox.Item label="Signalements" style={{color:'white'}} status={checked} onPress={handleCheck} />
        <Text style={{color:'white'}}>Type</Text>
        <Text style={{color:'white'}}>Région</Text>
        <Text style={{color:'white'}} id="modal-modal-description">
            
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
            height: 40, width: 120,
            display: 'flex',
            flexDirection:'row',
            alignItems: 'center',
            justifyContent: 'center',
            borderRadius: 5,
            backgroundColor: '#3CAA4A'
            }}
            onPress={() => handleClose()}>
            <Icon name='filter-alt' size={25} color={'#f4f4f4'}/>
            <Text style={{color: '#f4f4f4'}}>Appliquer</Text>
        </TouchableOpacity>     
        <TouchableOpacity
            mode='contained'
            style={{ 
            height: 40, width: 120,
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
    </View>
      
   return (
    <TouchableOpacity title="filter" mode='contained'
        style={{ 
            height:'100%', width: '100%',
            display: 'flex',
            flexDirection:'row',
            alignItems: 'center',
            justifyContent: 'center',
            // borderRadius: '50%',
        }}
        onPress={() => handleOpen()}>
        <Icon name='filter-alt' size={25} color={'#f4f4f4'}/>
    </TouchableOpacity>    
  );
}