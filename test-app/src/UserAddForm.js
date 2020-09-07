import React from 'react';
import './UserAddForm.css';
import Alert from './Alert';
import useDataApi from './DataApi';
import Form from './Form';
import {fields, addressFields} from './userschema';

const UserAddForm = (props) => {

  const createUrl = 'http://localhost:4040/api/user';
  const [{ data, isLoading, isError }, , setParams] = useDataApi(
    createUrl,
    {},
    null,
    'post'
  );

  const validationErrors = isError && data.response.data.errors ? data.response.data.errors : null;

  if (data.created) {
    return <div className="App-conainer">
        <Alert className="success">{data.message}</Alert>
      </div>;
  }

  return <div className="App-conainer UserAddForm">
      {isError && <Alert className="danger">{data.response.statusText
        ? data.response.statusText
        : data.message}</Alert>}
      { validationErrors && Object.values(validationErrors).map(
        (eArr) => eArr.map((e, id) => <Alert key={id} className="warning">{e}</Alert>)
      ) }
      {isLoading && <Alert className="info">Loading...</Alert>}
          <div className="App-block">
            <h2 className="App-heading">Create new user</h2>
            <Form id="create" fields={fields} addressFields={addressFields} onDataSubmit={setParams}/> 
          </div>
    </div>;
}

export default UserAddForm;
