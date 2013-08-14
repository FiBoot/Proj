#ifndef CLIENT_H
#define CLIENT_H

#include <QTcpSocket>
#include <QtNetwork>

class Client : public QObject
{
    Q_OBJECT

public:
    Client();
    ~Client();
    void    startClient(QString, int port);
    void    sendFile(QString);
    void    receiveFile(QString);

public slots:
    void    receiveData();

private:
    QTcpSocket  clientSocket;
};

#endif // CLIENT_H
