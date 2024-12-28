import React from 'react';
import { Row, Col } from 'react-bootstrap';
// import { Pagination as Paginate } from 'react-laravel-paginex'

export default function Pagination(props) {
    const options = {
        nextButtonText: 'Próxima',
        prevButtonText: 'Anterior'
    };

    //return null;
    return (
        <>
            <Row style={{ padding: '5px' }}>
                {/* <Col>
                    <Paginate 
                        {...props}
                        options={options}
                    />
                </Col>
                <Col style={{ textAlign: 'right' }}>
                    <p>Monstrando {props.data.to || 0} de {props.data.total || 0} registros.</p>
                </Col> */}
            </Row>
        </>
    );
}