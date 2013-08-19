#include        "mainwindow.h"
#include        "ui_mainwindow.h"

MainWindow::MainWindow(QWidget *parent) : QMainWindow(parent), ui(new Ui::MainWindow)
{
    ui->setupUi(this);
    ui->groupServer->hide();
    ui->groupClient->hide();
	
    logWindow   = NULL;
    port 		= 4242;
}

MainWindow::~MainWindow()
{
    delete ui;
}


// private slots
void                MainWindow::on_launchServerButton_clicked()
{
    ui->groupButton->hide();
    ui->groupServer->show();
    server.launchServer(port);
	
	// list files
	std::vector<FileInfo *> files	= listFiles();
    for (std::vector<FileInfo *>::iterator it = files.begin();it != files.end(); ++it)
    {
        std::cout << "name: " << qPrintable((*it)->getName()) << std::endl;
        std::cout << "size: " << (*it)->getSize() << std::endl;
        std::cout << "lastModified: " << qPrintable((*it)->getLastModified().toString()) << std::endl;
        std::cout << std::endl;
    }
	
}

void                MainWindow::on_connectionButton_clicked()
{
    if (logWindow == NULL)
    {
        logWindow   = new LogWindow();
        connect(logWindow, SIGNAL(connection(QString, QString)), this, SLOT(connection(QString, QString)));
    } else {
        logWindow->hide();
    }
    logWindow->show();
}

void                MainWindow::on_stopServerButton_clicked()
{
    ui->groupServer->hide();
    ui->groupButton->show();
}

void                MainWindow::on_clientExitButton_clicked()
{
    ui->groupClient->hide();
    ui->groupButton->show();
}

void                MainWindow::on_getFile_clicked()
{
}

void                MainWindow::on_sendFile_clicked()
{
}


// public slots
void                MainWindow::connection(QString login, QString ip)
{
    ui->groupButton->hide();
    ui->groupClient->show();
    client.startClient(ip, port);
}
