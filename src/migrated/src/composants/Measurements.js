import * as React from 'react';
import { View } from 'react-native';

export default function Measurements({ navigation }) {
    return (
        <>
        <View style={{ 
          // borderWidth:1, borderColor:'red', 
          backgroundColor: 'rgba(0,0,0,0.5)',
          position: 'absolute', width: 80, height: '100%',
          zIndex: 4,
          top: 0,
          right: 0
        }}>
            <View style={{ 
                borderRightWidth:1, borderColor:'red', 
                backgroundColor: 'rgba(0,0,0,0)',
                width: '50%', height: '100%',
            }}>
            </View>
        </View>

        <View style={{ 
            // borderWidth:1, borderColor:'red', 
            backgroundColor: 'rgba(0,0,0,0.5)',
            position: 'absolute', height: 80, width: '100%',
            zIndex: 4,
            top: 0,
            right: 0
        }}>
            <View style={{ 
                borderBottomWidth:1, borderColor:'red', 
                backgroundColor: 'rgba(0,0,0,0)',
                height: '62.5%', width: '100%',
            }}>
            </View>
        </View>

        <View style={{ 
            // borderWidth:1, borderColor:'red', 
            backgroundColor: 'rgba(0,0,0,0.5)',
            position: 'absolute', width: 80, height: '100%',
            zIndex: 4,
            top: 0,
            left: 0
        }}>
            <View style={{ 
                borderRightWidth:1, borderColor:'red', 
                backgroundColor: 'rgba(0,0,0,0)',
                width: '50%', height: '100%',
            }}>
            </View>
        </View>
        </>
    );
}