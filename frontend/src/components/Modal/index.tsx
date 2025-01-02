import { Modal as ModalBoot } from 'react-bootstrap';

export default function Modal(props: any) {
    return (
        <ModalBoot size={props.size || 'sm-down'} show={props.show} onHide={props.handleClose}>
            <ModalBoot.Header closeButton>
                <ModalBoot.Title><h5>{props.title}</h5></ModalBoot.Title>
            </ModalBoot.Header>
            <ModalBoot.Body>
                {props.children}
            </ModalBoot.Body>
            <ModalBoot.Footer>
                
            </ModalBoot.Footer>
        </ModalBoot>
    );
}