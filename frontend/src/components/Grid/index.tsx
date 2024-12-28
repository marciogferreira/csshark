import { Table, Button } from 'react-bootstrap';
import { FiEdit } from 'react-icons/fi';
import { MdClose } from 'react-icons/md';

type DataProps = {
    list: any[],
    fields: any[],
    titleBtnEdit: string,
    enableBtnEdit: boolean,
    handleEdit: (item: any) => void,
    enableBtnDelete: boolean,
    handleDelete: (item: any) => void,
    handleCustomEdit?: (item: any) => void,
    fieldsHtml?: any,
    enableBtnNew: boolean,
    handleNew: () => void
}

export default function Grid(props: DataProps) {
    
    const FieldsHtml = props.fieldsHtml;

    return (
        <Table>
            <thead>
                <tr>
                    {props.fields.map((item, index) => (
                        <th key={index} >{item.label}</th>
                    ))}
                    {props.enableBtnEdit && props.enableBtnEdit &&
                        <th style={{ width: '10%' }}>Ações</th>
                    }
                </tr>
            </thead>
            <tbody>
                {props.list && props.list.map((item, idx) => (
                    <tr key={idx}>
                        {props.fieldsHtml && <FieldsHtml {...props} item={item}  />}
                        {!props.fieldsHtml && 
                            <>
                                {props.fields.map(i => (
                                    <td className={i.classBody || ''}>{item[i.name]}</td>
                                ))}
                            </>
                        }
                        {!props.enableBtnEdit && !props.enableBtnEdit &&
                            <td style={{ width: '12%' }}>
                                {!props.enableBtnEdit && 
                                    <Button size="sm" variant="primary" onClick={() => {
                                        if(props.handleCustomEdit) {
                                            props.handleCustomEdit(item)
                                        } else {
                                            props.handleEdit(item)
                                        }
                                    }}>
                                        {props.titleBtnEdit ? props.titleBtnEdit : <FiEdit color="#FFF" />}
                                    </Button>
                                }
                                &nbsp;
                                {!props.enableBtnDelete && 
                                    <Button size="sm" variant="danger" onClick={() => props.handleDelete(item)}>
                                        <MdClose  color="#FFF"/>
                                    </Button>
                                }
                            </td>
                        }
                    </tr>
                ))}
            </tbody>
        </Table>
    );
}


