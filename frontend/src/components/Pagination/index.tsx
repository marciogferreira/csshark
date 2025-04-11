import { Col, Row } from 'react-bootstrap';
// import { Pagination as Paginate } from 'react-laravel-paginex'
// import { Pagination as Paginate } from "react-laravel-pagination";

export default function Pagination(props: any) {
    // const options = {
    //     nextButtonText: 'Próxima',
    //     prevButtonText: 'Anterior'
    // };
    console.log(props.data)

    //return null;
    return (
        <>
            <Row style={{ padding: '5px' }}>
                <Col>
                    <button 
                        className={`btn btn-primary btn-sm ${props.data.current_page <= 1 ? 'disable': ''} link`}
                        onClick={() => props.onChange(props.data.current_page - 1)}
                        >
                        Anterior
                    </button>
                    &nbsp;
                    <button 
                        className={`btn btn-primary btn-sm`}
                        onClick={() => props.onChange(props.data.current_page + 1)}
                    >
                        Próxima
                    </button>
                </Col>
                <Col style={{ textAlign: 'right' }}>
                    <p>Monstrando {props.data.to || 0} de {props.data.total || 0} registros.</p>
                </Col>
            </Row>
        </>
    );
}