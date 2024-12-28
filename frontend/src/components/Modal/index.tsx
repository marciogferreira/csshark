import React from 'react';
import { Modal as ModalBoot } from 'react-bootstrap';

export default function Modal(props) {
    return (
        <ModalBoot size={props.size} show={props.show} onHide={props.handleClose}>
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

Modal.defaultProps = {
    size: "sm-down",
};