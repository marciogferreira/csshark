import { Button, FormGroup } from 'react-bootstrap';

// type DataProps = {
//     enableBtnSave: boolean,
//     enableBtnCancel: boolean,
//     titleBtnSave: string,
//     titleBtnCancel: string,
//     handleSave: () => void,
//     handleCancel: () => void  // Function to call when cancel button is clicked.
// }

export default function FormButtons(props: any) {
    return (
        <FormGroup style={{ marginTop: '10px', float: 'right' }}>
            {!props.enableBtnSave && 
                <Button size="sm" variant="success" onClick={props.handleSave}>{props.titleBtnSave || 'Salvar'}</Button>
            }
            &nbsp;
            {!props.enableBtnCancel && 
                <Button size="sm" variant="warning" onClick={props.handleCancel}>{props.titleBtnCancel || 'Cancelar'}</Button>
            }
        </FormGroup>
    );
}