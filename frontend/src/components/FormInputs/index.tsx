
import { Form, FormGroup } from 'react-bootstrap';

export function Select(props: any) {
    return (
        <>
            <FormGroup>
                <Form.Label className={props.required && `required`}  htmlFor={props.id}>{props.label}</Form.Label>
                <Form.Control 
                    as="select" 
                    custom  
                    {...props} 
                    {...props.field} 
                    onChange={e => {
                        if(props.handleChange) {
                            props.handleChange(e.target.value);
                        } else {
                            props.form.setFieldValue(props.field.name, e.target.value)
                        }
                    }}
                >
                    <option value="">{props.placeholder}</option>
                    {props.list.map((item: any) => (
                        <option value={item.value}>{item.label}</option>
                    ))}
                </Form.Control>
            </FormGroup>
            <div><span className="error">{props.error}</span></div>
        </>
    );
}