  const fields = [
    {
      id: 'name',
      label: 'Name:',
      type: 'text',
      required: true,
    },
    {
      id: 'lastname',
      label: 'Lastname:',
      type: 'text',
      required: true,
    },
    {
      id: 'email',
      label: 'Email(login):',
      type: 'email',
      required: true,
    },
    {
      id: 'password',
      label: 'Password:',
      type: 'password',
      required: true,
    },
    {
      id: 'password_confirmation',
      label: 'Password confirmation:',
      type: 'password',
      required: true,
    },
    {
      id: 'type_aw',
      label: 'Administration worker:',
      type: 'checkbox',
      defaultValue: false,
    },
    {
      id: 'type_l',
      label: 'Lecturer:',
      type: 'checkbox',
      defaultValue: false,
    },
    {
      id: 'phone',
      label: 'Phone number:',
      type: 'string',
      required: true,
    },
    {
      id: 'education',
      label: 'Education degree:',
      type: 'select',
      dataset: ['licencjat', 'inżynier', 'magister', 'doktor', 'doktor habilitowany'],
      required: true,
    },
  ];

  const addressFields = [
    {
      id: 'street',
      label: 'Street:',
      type: 'text',
      required: true,
    },
    {
      id: 'number',
      label: 'House number:',
      type: 'text',
      required: true,
    },
    {
      id: 'city',
      label: 'City:',
      type: 'text',
      required: true,
    },
    {
      id: 'code',
      label: 'Code:',
      type: 'text',
      required: true,
    },
    {
      id: 'region',
      label: 'Region:',
      type: 'text',
      required: true,
    },
    {
      id: 'country',
      label: 'Country:',
      type: 'text',
      required: true,
    }
  ];

export {fields, addressFields};
