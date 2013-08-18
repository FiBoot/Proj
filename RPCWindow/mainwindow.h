#ifndef         MAINWINDOW_H
#define         MAINWINDOW_H

#include        <QMainWindow>
#include        <iostream>
#include        "logwindow.h"
#include 		"server.h"
#include 		"client.h"

namespace Ui {
    class MainWindow;
}

class MainWindow : public QMainWindow
{
    Q_OBJECT
    
public:
    explicit MainWindow(QWidget *parent = 0);
    ~MainWindow();

public slots:
    void            connection(QString, QString);

private slots:
    void            on_launchServerButton_clicked();
    void            on_connectionButton_clicked();
    void            on_stopServerButton_clicked();
    void            on_clientExitButton_clicked();
    void            on_getFile_clicked();
    void            on_sendFile_clicked();

private:
    Ui::MainWindow  *ui;
    LogWindow       *logWindow;
    Server  		server;
    Client  		client;
    int     		port;
};

#endif // MAINWINDOW_H
