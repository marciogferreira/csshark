import React, { createContext, useEffect, useState } from 'react';
import Util from 'src/core/Util';
import Api from 'src/core/api';
import PaperOrder from 'src/components/PaperOrder';
import HistoryStatusOrder from 'src/components/HistoryStatusOrder';
import AlterarStatus from 'src/components/AlterarStatus';

const HelperContext = createContext({ list: [] });

export const HelperProvider = ({ children }) => {

    const [viewPedido, setViewPedido] = useState('');
    const [viewHistoryPedido, setViewHistoryPedido] = useState('');
    const [viewAlterStatus, setViewAlterStatus] = useState('');

    return (
        <HelperContext.Provider value={{ 
            viewPedido,
            setViewPedido,
            viewHistoryPedido,
            setViewHistoryPedido,
            viewAlterStatus,
            setViewAlterStatus
        }}>
            <PaperOrder />
            <HistoryStatusOrder />
            <AlterarStatus />
            {children}
        </HelperContext.Provider>
    )
}

export default HelperContext;
