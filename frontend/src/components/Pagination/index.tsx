import { Row } from 'react-bootstrap';
// import { Pagination as Paginate } from 'react-laravel-paginex'

export default function Pagination(props: any) {
    // const options = {
    //     nextButtonText: 'Próxima',
    //     prevButtonText: 'Anterior'
    // };
    console.log(props)

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