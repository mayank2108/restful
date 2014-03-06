
Ext.require(['Ext.data.*', 'Ext.grid.*']);

Ext.define('Product', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int',
        useNull: true
    }, 'name', 'quantity', 'price', 'description', 'is_in_stock'],
    validations: [{
        type: 'length',
        field: 'name',
        min: 1
    }, {
        type: 'length',
        field: 'quantity',
        min: 1
    }, {
        type: 'length',
        field: 'price',
        min: 1
    }, {
        type: 'length',
        field: 'description',
        min: 3
    }]
});

Ext.onReady(function(){

    var store = Ext.create('Ext.data.Store', {
        autoLoad: true,
        autoSync: true,
        model: 'Product',
        proxy: {
            type: 'rest',
            url: 'server/app.php/products',
            reader: {
                type: 'json',
                root: 'data'
            },
            writer: {
                type: 'json'
            }
        },
        listeners: {
            write: function(store, operation){
                //console.log(operation)
                var record = operation.getRecords()[0],
                    name = Ext.String.capitalize(operation.action),
                    verb;
                    
                    
                if (name == 'Destroy') {
                    record = operation.records[0];
                    verb = 'Destroyed';
                } else {
                    verb = name + 'd';
                }
               // console.log(verb);
              //  console.log(record);

                Ext.Msg.alert(name, (verb  +" product: "+ record.getId()));
                store.load();
            }
        }
    });


     
    
    var rowEditing = Ext.create('Ext.grid.plugin.RowEditing');
    
    var grid = Ext.create('Ext.grid.Panel', {
        renderTo: document.body,
        plugins: [rowEditing],
        width: '100%',
        height: 600,
		forcefit:true,
        frame: true,
        title: 'Products',
        store: store,
        iconCls: 'icon-user',
        columns: [{
            text: 'PID',
            width: 40,
            sortable: true,
            dataIndex: 'id'
        }, {
            text: 'Name',
            width: 220,
            sortable: true,
            dataIndex: 'name',
            field: {
                xtype: 'textfield'
            }
        }, {
            header: 'Quantity',
            width: 80,
            sortable: true,
            dataIndex: 'quantity',
            field: {
                xtype: 'numberfield'
            }
        }, {
            text: 'Price',
            width: 60,
            sortable: true,
            dataIndex: 'price',
            field: {
                xtype: 'numberfield'
            }
        }, {
            text: 'Description',
            flex: 1,
            sortable: true,
            dataIndex: 'description',
            field: {
                xtype: 'textfield'
            }
        }, {
            text: 'In stock',
            width: 50,
            sortable: true,
            dataIndex: 'is_in_stock',
            field: {
                xtype: 'checkbox'
            },
			falseText:'No',
			trueText:'Yes'
        } ],
        dockedItems: [{
            xtype: 'toolbar',
            items: [{
                text: 'Add',
                iconCls: 'icon-add',
                handler: function(){

                    store.insert(0, new Product());
                    rowEditing.startEdit(0, 0);

                },
				tooltip: 'Add New Product'
            }, '-', {
                itemId: 'delete',
                text: 'Delete',
                iconCls: 'icon-delete',
                disabled: true,
                handler: function(){
                    var selection = grid.getView().getSelectionModel().getSelection()[0];
                    if (selection) {
                        store.remove(selection);
                       // store.load();
                    }
                },
				tooltip: 'Remove selected Product'
            }, '-', '->', {
                itemId: 'searchbox',
				id:'searchId',
                xtype:'textfield',
				emptyText: 'Search here...'
            }, {
                itemId: 'search',
                text: 'Search',
                iconCls: 'icon-search',
                handler: function(){
                   // console.log(Ext.getCmp('searchId'));
                    var searchText = Ext.getCmp('searchId').getValue();

					store.load({params: {searchtxt: searchText}});
                }
            }]
        }]
    });
    grid.getSelectionModel().on('selectionchange', function(selModel, selections){
        grid.down('#delete').setDisabled(selections.length === 0);
    });
});

