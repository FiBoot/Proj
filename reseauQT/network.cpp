#include "network.h"
#include <iostream>

void    Network::sendFile(QString fileName, QTcpSocket * socket)
{
    QFile inputFile(fileName);
    if (inputFile.open(QIODevice::ReadOnly) == false)
    {
        std::cerr << "Can't open file '" << fileName.toStdString() << "'" << std::endl;
        return ;
    }
    int totalRead = 0;
    while (!inputFile.atEnd())
    {
        QByteArray block;
        QDataStream out(&block, QIODevice::WriteOnly);

        //etape 0 la config
        out.setVersion(QDataStream::Qt_5_1);
        out << (quint16)0;

        //etape 1 la commande
        QString cmd = "sendFile";
        out << cmd;

        out << totalRead;
        std::cout << "DEBUG totalRead=" << totalRead << std::endl;

        //etape 2 le nom du fichier
        out << fileName;

        //etape 3 la data
        QByteArray data;
        data.clear();
        data = inputFile.read(4096);
        totalRead += data.size();
        out << data;

        //etape 4 on envoie
        out.device()->seek(0);
        out << (quint16)(block.size() - sizeof(quint16));
        if (socket->write(block) == -1)
        {
            std::cerr << "Error write socket" << std::endl;
        }
    }

    inputFile.close();
}

void    Network::receiveFile(QDataStream * in)
{
    int totalRead = 0;
    (*in) >> totalRead;
    std::cout << "DEBUG totalRead=" << totalRead << std::endl;

    QString fileName;
    (*in) >> fileName;

    if (fileName.compare("") == 0)
    {
        return ;
    }

    QFile file(fileName);
    if (totalRead == 0)
    {
        if(!(file.open(QIODevice::ReadWrite | QIODevice::Truncate)))
        {
            std::cout << "File cannot be opened." << std::endl;
            return ;
        }
    }
    else
    {
        if(!(file.open(QIODevice::Append)))
        {
            std::cout << "File cannot be opened." << std::endl;
            return ;
        }
    }

    QByteArray data;
    (*in) >> data;
    file.write(data);
    file.close();
}

void    Network::receiveData(QTcpSocket *socket)
{
    QDataStream in(socket);
    in.setVersion(QDataStream::Qt_5_1);

    while (1)
    {
        //wait requis pour le fonctionnemnt du transfert
        socket->waitForReadyRead(10);

        qint16 blockSize = 0;
        if (blockSize == 0)
        {
            if (socket->bytesAvailable() < (int)sizeof(quint16))
            {
                return ;
            }
            in >> blockSize;
        }
        if (socket->bytesAvailable() < blockSize)
        {
            return ;
        }

        QString cmd;
        in >> cmd;

        if (cmd.compare("sendFile") == 0)
        {
            Network::receiveFile(&in);
        }
        else if (cmd.compare("receiveFile") == 0)
        {
            QString fileName = "";
            in >> fileName;
            Network::sendFile(fileName, socket);
        }
    }
}
