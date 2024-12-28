import Crud from '../../components/Crud';
import { ReactElement, useEffect, useState } from 'react';
import Api from '../../core/api';
import { Select } from '../../components/FormInputs';

type DataProps = {
  Field: ReactElement | any;
  ErrorMessage: ReactElement | any;
}
const FormWrapper = ({ Field, ErrorMessage }: DataProps) => {
   

    const[professores, setProfessores] = useState([]);
    const[modalidades, setModalidades] = useState([]);

    async function getListas() {
        const response = await Api.get('colaborador/options');
        setProfessores(response.data.data);

        const responseM = await Api.get('modalidades/options');
        setModalidades(responseM.data.data);
    }

    useEffect(() => {
        getListas();
    }, [])

    return (
        <>
            <div className='row'>
                <div className="col-md-12">
                    <div className='mb-3'>
                        <label>Nome</label>
                        <Field name="nome" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="nome" component="span" />
                        </span>
                    </div>
                    <div className='mb-3'>
                        <label>Turno</label>
                        <Field name="turno" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="turno" component="span" />
                        </span>
                    </div>

                    <div className='mb-3'>
                        <label>Valor/Mensal</label>
                        <Field name="valor" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="valor" component="span" />
                        </span>
                    </div>

                    <div className='mb-3'>
                        <Field 
                            required
                            id="modalidade_id"
                            label="Modalidade"
                            name="modalidade_id"
                            placeholder="Selecione"
                            component={Select}
                            list={modalidades}
                            error={<ErrorMessage name="modalidade_id" />}
                        />
                    </div>    

                    <div className='mb-3'>
                        <Field 
                            required
                            id="colaborador_id"
                            label="Professor"
                            name="colaborador_id"
                            placeholder="Selecione"
                            component={Select}
                            list={professores}
                            error={<ErrorMessage name="colaborador_id" />}
                        />
                    </div>                    

                  
                </div>
                
            </div>           
        </>
    );
}

export default function TurmasIndex() {
    return (
        <Crud
            title="Turmas"
            endPoint="turmas"
            searchFieldName='search'
            emptyObject={{
                nome: '',
                turno: '',
                valor: '',
                colaborador_id: '',
                modalidade_id: '',
            }}
            fields={[
                { name: 'id', label: 'Id', classBody: 'min-width' },
                { name: 'nome', label: 'Nome' }
            ]}
            validation={(Yup: object | any) => {
                return {
                    nome: Yup.string().required('Campo obrigatório'),
                    turno: Yup.string().required('Campo obrigatório'),
                    valor: Yup.string().required('Campo obrigatório'),
                    modalidade_id: Yup.string().required('Campo obrigatório'),
                    colaborador_id: Yup.string().required('Campo obrigatório'),
                };
            } }
            FormWrapper={FormWrapper}
            columns={[]}
            enableBtnNew={false}
            saveContinueForm={false}
            showActionsColumn={false}
            showDeleteColumn={false}
            showEditColumn={false}
            showNewButton={false}
            showSearch={false}
            showPagination={false}
            showSort={false}
            showTotal={false}
            showViewHistory={false}
            showViewAlterStatus={false} fieldsHtml={undefined}        />
    );
}
