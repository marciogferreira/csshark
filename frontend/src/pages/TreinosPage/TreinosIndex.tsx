import { Select } from "../../components/FormInputs";
import Api from "../../core/api";
import lista_treinos from "./lista_treinos";
import { useEffect, useState } from "react";
import Crud from '../../components/Crud';
import Message from "../../core/Message";


type Treino = {
  [key: string]: any; // As propriedades podem ser string ou number
};
const FormWrapper = ({ Field, ErrorMessage, values }: any) => {
   
  const[treino, setTreino] = useState<Treino>(lista_treinos);
  const[alunos, setAlunos] = useState([]);
  
  async function getAlunos() {
    const response = await Api.get('alunos/options');
    setAlunos(response.data.data);
  }

  async function handleSubmit() {
    if(values.id) {
      await Api.put(`alunos-treinos/${values.id}`, {
        aluno_id: values.aluno_id,
        treino_id: values.treino_id,
        data: treino,
        observacao: values.observacao
      });
      Message.success("Treino Atualizado com Sucesso.")
    } else {
      await Api.post('alunos-treinos', {
        aluno_id: values.aluno_id,
        data: treino,
        observacao: values.observacao
      });
      Message.success("Treino Salvo com Sucesso.")
    }
    
  }

  async function handleTreino(name: any, id: any, item: any, value: any) {
    const lista = treino[name];
    const itemIndex = lista.findIndex((i: any) => i.id === id);
    lista[itemIndex][item] = value;
    setTreino({...treino, [name]: lista });
  }

  useEffect(() => {
    getAlunos();
    console.log(values.data)
    if(values.id) {
      setTreino(JSON.parse(values.data));
    }
  }, [])

  return (
    <>
      <Field
        required
        id="aluno_id"
        label="Selecione o Aluno"
        name="aluno_id"
        placeholder="Selecione"
        list={alunos}
        component={Select}
        error={<ErrorMessage name="aluno_id" />}
      />
      <br />
      <table className="table table-hover table-striped">
        <tbody>
          {Object.keys(treino).map((name: any) => (
            <>
              <tr>
                <th colSpan={4}>
                  <h4>{name.toUpperCase()}</h4>
                </th>
              </tr>

              {treino && treino[name].map((item: any, index: number) => (
                <tr key={index}>
                  <td>
                    
                    {item.exercicio}
                  </td>
                  <td>
                    <label>
                      <input
                        placeholder="Series"
                        type="number"
                        name="series"
                        className="form-control"
                        value={item.series}
                        onChange={e => handleTreino(name, item.id, 'series', e.target.value)}
                      />
                    </label>
                  </td>
                  <td>
                    <label>
                        <input
                          placeholder="Repts"
                          type="number"
                          name="reps"
                          className="form-control"
                          value={item.reps}
                          onChange={e => handleTreino(name, item.id, 'reps', e.target.value)}
                        />
                    </label>
                  </td>
                  <td>
                    <label>
                      
                        <input
                          placeholder="Obs"
                          type="number"
                          name="obs"
                          className="form-control"
                          value={item.obs}
                          onChange={e => handleTreino(name, item.id, 'obs', e.target.value)}
                        />
                    </label>
                  </td>
                </tr>
              ))}
            </>
          ))}  
        </tbody>
      </table>
      <div>
        <Field
          id="observacao"
          data="observacao"
          label="Observações"
          name="observacao"
          className="form-control"
          type="textarea"
          placeholder="Digite aqui as observações..."
          error={<ErrorMessage name="observacao" />}
        />
      </div>
      <div className="d-flex justify-content-end mt-2 mb-5">
        <button type="button" className="btn btn-primary" onClick={() => handleSubmit()}>Salvar Treino</button>
      </div>
    </>
)
}

export default function TreinosIndex() {
    return (
        <Crud
            title="Treinos de Alunos"
            endPoint="alunos-treinos"
            searchFieldName='search'
            emptyObject={{
                nome: '',
                aluno_id: '',
                treino_id: '',
                observacao: '',
                data: {}
            }}
            fields={[
                { name: 'id', label: 'Id', classBody: 'min-width' },
                { name: 'nome', label: 'Nome' },
                { name: 'cpf', label: 'CPF' }
            ]}
            fieldsHtml={({ item } : any) => (
              <>
                  <td>{item.id}</td>
                  <td>{item.aluno.nome}</td>
                  <td>{item.aluno.cpf}</td>
              </>
          )}
            validation={(Yup: object | any) => {
                return {
                   nome: Yup.string().required('O nome é obrigatório')
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
            showViewAlterStatus={false}        
          />
    );
}
