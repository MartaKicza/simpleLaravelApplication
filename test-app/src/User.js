import React, {useState, useEffect} from 'react';
import './User.css';
import Button from './Button';
import Alert from './Alert';
import {fields, addressFields} from './userschema';
import Form from './Form';
import useDataApi from './DataApi';

function User({ user, isLoading, isError, deleted, userId, userLogId, onUserEdited, ...props }) {
  const [edit, setEdit] = useState(false);

  const editUrl = 'http://localhost:4040/api/user/'+user.id;
  const [{ data, isLoading: editIsLoading, isError: editIsError }, , setParams] = useDataApi(
    editUrl,
    {},
    null,
    'put'
  );

  useEffect(() => {
    if (data.updated) {
      setEdit(false);
      onUserEdited();
    }
  }, [data, onUserEdited]);

  const _createUserDetails = () => {
    const details = [
      ['Name', user.name],
      ['Lastname', user.lastname],
      ['Email', user.email],
      ['Type', (user.type_aw ? 'Administration Worker' : '') + ' ' + (user.type_l ? 'Lecturer' : '')],
    ];
    if (user.type_l) {
      details.push(['Phone', user.phone]);
      details.push(['Education', user.education]);
    }
    if (user.type_aw) {
      details.push(['Address', 
        <div key={user.address.id} className="User-address">
        {user.address.street}
        {' '}
        {user.address.number}
        <br />
        {user.address.code}
        {' '}
        {user.address.city}
        <br />
        {user.address.region}
        {' '}
        {user.address.country}
        </div>]);
      details.push(['Correspondal address', 
        <div key={user.correspondal_address.id} className="User-address">
        {user.correspondal_address.street}
        {' '}
        {user.correspondal_address.number}
        <br />
        {user.correspondal_address.code}
        {' '}
        {user.correspondal_address.city}
        <br />
        {user.correspondal_address.region}
        {' '}
        {user.correspondal_address.country}
        </div>]);
    }
    return details;
  }
  const _fillFields = (f, obj) => {
    return f.map(
      (field) => {
        let new_f = {...field};
        if (obj[field.id]) {
          new_f.defaultValue = obj[field.id];
        }
        return new_f;
      }
    );
  }

  if (deleted.deleted && user.id === userId) {
    userLogId(0);
    return <div className="App-conainer User">
        <Alert className="success">{deleted.message}</Alert>
      </div>
  }

  let userBlock = <div>No results</div>;
  if (user) {
    if (edit) {
      let filledFields = _fillFields(fields, user);
      filledFields.find((a) => a.id === 'password')['required'] = false;
      filledFields.find((a) => a.id === 'password_confirmation')['required'] = false;
      let filledAddressFields = addressFields;
      let filledCorrespondalAddressFields = addressFields;
      if (user.address_id) {
        filledAddressFields = _fillFields(filledAddressFields, user.address);
      }
      if (user.correspondal_address_id) {
        filledCorrespondalAddressFields = _fillFields(filledCorrespondalAddressFields, user.correspondal_address);
      }
      let validationErrors = editIsError && data.response.data.errors ? data.response.data.errors : null;
      if (typeof validationErrors != 'object') {
        validationErrors = [[validationErrors]];
      }
      console.log(validationErrors);

      userBlock = <div>
        {editIsLoading && <Alert class="info">Loading...</Alert>}
        {editIsError && <Alert className="danger">{data.response.statusText
                ? data.response.statusText
                : data.message}</Alert>}
        { validationErrors && Object.values(validationErrors).map(
          (eArr) => eArr.map((e, id) => <Alert key={id} className="warning">{e}</Alert>)
        ) }
          <Form id="edit" 
          fields={filledFields} 
          addressFields={filledAddressFields} 
          correspondalAddressFields={filledCorrespondalAddressFields} 
          buttonLabel="Save"
          onDataSubmit={setParams}/> 
        </div>
    } else {
      const details = _createUserDetails();

      userBlock = <div className="User-content">
        {details.map(([name, value], id) => {
          return <div className="User-detail" key={id}>
            <span className="User-detail-name">{name}: </span> {value}
            </div>;
        })}
        <div className="User-footer">
        <Button 
      clickAction={props.onUserDeleteClick.bind(null, user)}
      className="Button-danger"
        >Delete</Button>
        <Button 
      clickAction={() => setEdit(true)}
      className="Button-success"
        >Edit</Button>
        </div>
        </div>;
    }
  }
  return <div className="App-conainer User">
        {isError && <Alert className="danger">An error occured</Alert>}
        {isLoading ? (
          <Alert className="info">Loading...</Alert>
        ) : (
            <div className="App-block">
              <h2 className="App-heading">User details</h2>
              {userBlock} 
            </div>
        )}
      </div>
};

export default User;
