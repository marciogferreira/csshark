import { Col, Row } from 'react-bootstrap';
// import { Pagination as Paginate } from 'react-laravel-paginex'
// import { Pagination as Paginate } from "react-laravel-pagination";

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
                <Col>
                    <a 
                        href="#" 
                        className={`${props.current <= 1 ? 'disable': ''} link`}
                        onClick={() => props.onChange(props.current - 1)}
                        >
                            Anterior
                    </a>
                    &nbsp;
                    <a 
                        href="#" 
                        onClick={() => props.onChange(props.current + 1)}
                    >
                        Próxima
                    </a>
                </Col>
                <Col style={{ textAlign: 'right' }}>
                    <p>Monstrando {props.data.to || 0} de {props.data.total || 0} registros.</p>
                </Col>
            </Row>
        </>
    );
}