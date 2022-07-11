<?php

namespace app\models;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::find()->where(['name' => $username])->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }




    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'password'], 'required'],
            [['rule'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 320],
            [['phone'], 'string', 'max' => 18],
            [['password'], 'string', 'max' => 32],
            [['name'], 'unique'],
            [['email'], 'unique'],
            [['phone'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'password' => 'Password',
            'rule' => 'Rule',
        ];
    }

    /**
     * Gets query for [[Friends]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFriends()
    {
        return $this->hasMany(Friend::className(), ['idUser' => 'id']);
    }

    /**
     * Gets query for [[Friends0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFriends0()
    {
        return $this->hasMany(Friend::className(), ['idFriend' => 'id']);
    }

    /**
     * Gets query for [[IdFriends]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdFriends()
    {
        return $this->hasMany(User::className(), ['id' => 'idFriend'])->viaTable('friend', ['idUser' => 'id']);
    }

    /**
     * Gets query for [[IdMusics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdMusics()
    {
        return $this->hasMany(Music::className(), ['id' => 'idMusic'])->viaTable('mymusic', ['idUser' => 'id']);
    }

    /**
     * Gets query for [[IdUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'idUser'])->viaTable('friend', ['idFriend' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['idUser' => 'id']);
    }

    /**
     * Gets query for [[Multimedia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMultimedia()
    {
        return $this->hasMany(Multimedia::className(), ['idUser' => 'id']);
    }

    /**
     * Gets query for [[Musics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMusics()
    {
        return $this->hasMany(Music::className(), ['idUser' => 'id']);
    }

    /**
     * Gets query for [[Mymusics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMymusics()
    {
        return $this->hasMany(Mymusic::className(), ['idUser' => 'id']);
    }
}
